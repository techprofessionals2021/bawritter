<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\CartType;
use App\Models\Order;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Events\NewCommentEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\OrderResource;

use Mews\Purifier\Facades\Purifier;
use App\Events\DeliveryAcceptedEvent;
use App\Events\OrderStatusChangedEvent;
use Yajra\DataTables\Facades\DataTables;
use App\Events\RequestedForRevisionEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderIndexResource;
use App\Models\Rating;
use App\Services\CalculatorService;
use App\Services\CartService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Auth;

class OrderApiController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data['statistics'] = Order::apiStatistics();
        return apiResponseSuccess($data, 'status counts');

    }

    public function search()
    {

        $dropdowns = [
            'staff_list' => [
                '' => 'All',
                'unassigned' => 'Unassigned',
            ],
            'order_status_list' => [
                '' => 'All',
            ],
            'dead_line_list' => [
                '' => 'N/A',
                'today' => 'Today',
                'tomorrow' => 'Tomorrow',
                'day_after_tomorrow' => 'The day after tomorrow',
            ],
        ];

        return apiResponseSuccess($dropdowns, 'Searching_dropdown');

    }


    public function datatable(Request $request)
    {


        // dd(Auth::id());
        $query = Order::with([
            'assignee',
            'customer'
        ])->where('customer_id', Auth::id());

        // $query = Order::with([
        //     'assignee',
        //     'customer',
        //     'attachments'
        // ]);

        if ($request->order_number) {
            $query->where('number', $request->order_number);
        }

        if ($request->order_status_id) {
            $query->where('order_status_id', $request->order_status_id);
        }

        if ($request->staff_id) {
            if ($request->staff_id == 'unassigned') {
                $query->whereNull('staff_id');
            } else {
                $query->where('staff_id', $request->staff_id);
            }
        }

        $orders = $query->orderByDesc('id')->paginate(10);

        // return apiResponseSuccess(['data' => $orders], 'Order Retrieved Successfully.!');
        return apiResponseSuccess([
            'data' => OrderIndexResource::collection($orders),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ], 'Orders retrieved successfully!');


    }


    function show($id)
    {
        // $data = Order::find($id);

        // return apiResponseSuccess($data, 'order detail');

        $order = Order::findOrFail($id);

        return apiResponseSuccess(new OrderResource($order), 'Order detail');
    }

    public function quote(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('order_page');
        }

        $data = Order::dropdown();
        $data['title'] = 'Get an instant quote';
        return view('order.create', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data = Order::dropdown();
        $data['title'] = 'Let\'s get started on your project!';

        return apiResponseSuccess($data, 'order created Successfully!');
    }


    // store order function

    public function storeApiOrderInSession(StoreOrderRequest $request, CalculatorService $calculator, CartService $cart)
    {
        // dd(Auth::id());
        $data = $request->validated();

        // dd(Auth::id());

        $data = array_merge($data, $calculator->calculatePrice($data));

        $data['staff_id_from_client'] = isset($data['writer_model']['id']) ? $data['writer_model']['id'] : 0;
        $data['customer_id'] = Auth::id();

        $data['cart_total'] = $data['total'];
        $data['staff_payment_amount'] = $calculator->staffPaymentAmount($data['cart_total']);
        $data['title'] = Purifier::clean($request->input('title'));
        $data['instruction'] = Purifier::clean($request->input('instruction'));

        $orderService = app()->make('App\Services\OrderService');


        $order = $orderService->create($data);

        $cart->setCart([
            'order_id' => $order->id,
            'order_number' => $order->number,
            'cart_total' => $data['cart_total']
        ], CartType::NewOrder);

        session()->flash('success', 'Order has been saved. Please make the payment to confirm it');

        return apiResponseSuccess($order, 'order has been stored');

    }

    public function postComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        // if (!auth()->user()->hasRole('admin')) {
        //     if (
        //         !in_array(auth()->user()->id, [
        //             $order->customer_id,
        //             $order->staff_id
        //         ])
        //     ) {
        //         return response()->json([
        //             'status' => 'error',
        //             'message' => 'Unauthorized access'
        //         ], 403);
        //     }
        // }

        $message = Purifier::clean($request->input('message'));
        if ($message) {
            $comment = new Comment();
            $comment->body = $message;
            $comment->user_id = auth()->user()->id;
            $order->comments()->save($comment);

            // Dispatching Event
            event(new NewCommentEvent($comment));

            return apiResponseSuccess('Message Sent', 'Your Message has been sent successfully.');
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to post comment'
        ], 500);
    }

    public function rating_store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'comment' => 'sometimes|max:500',
            'number' => 'required'
        ], [
            'number.required' => 'Please choose a rating'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $request['order_id'] = $request->order_id;
        $request['user_id'] = $request->user_id;


        $rating = Rating::create($request->all());

        return apiResponseSuccess('Rating Saved', 'Thank you for your feedback!');
    }

    function download(Request $request)
    {
        // dd($request->file);

        $filePath = $request->input('file');

        $fileName = basename($filePath);

        // dd($filePath);

        // Define the destination path in the downloads folder
        $destinationPath = 'downloads/' . $fileName;
        Storage::disk('local')->move($filePath, $destinationPath);

        $fileUrl = Storage::disk('local')->url($destinationPath);
        return apiResponseSuccess($fileUrl, 'Attachment url');

    }

    public function change_status(Request $request, Order $order)
    {

        $validator = Validator::make($request->all(), [
            'order_status_id' => 'required'
        ], [

            'order_status_id.required' => 'Order status is required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $previous = $order->status->name;
        $order->order_status_id = $request->order_status_id;
        $order->save();
        $new = $order->status->name;

        // Dispatching Event
        event(new OrderStatusChangedEvent($order, $previous, $new));

        return apiResponseSuccess('Status Updated', 'Status Updated Success fully');
    }

    public function acceptSubmittedWork(Request $request, $id)
    {
        $order = Order::find($id);

        if(!$order){
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        if (auth()->user()->id != $order->customer_id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'submitted_work_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $order->order_status_id = ORDER_STATUS_COMPLETE;
        $order->save();

        event(new DeliveryAcceptedEvent($order));

        return apiResponseSuccess('Order Accepted', 'Order Completed Successfully.');
    }

    public function reviseSubmittedWork(Request $request, $id)
    {
        $order = Order::find($id);

        if(!$order){
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        if (auth()->user()->id != $order->customer_id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $submitted_work = $order->latest_submitted_work();

        if ($submitted_work->count() > 0) {
            $submitted_work->needs_revision = TRUE;
            $submitted_work->customer_message = Purifier::clean($request->message);
            $submitted_work->save();

            // Update Order Status
            $order->order_status_id = ORDER_STATUS_REQUESTED_FOR_REVISION;
            $order->save();

            // Dispatching Event
            event(new RequestedForRevisionEvent($order));
        }

        return apiResponseSuccess('Revision Request Sent', 'Order Revision Request Sent.');
    }

}

