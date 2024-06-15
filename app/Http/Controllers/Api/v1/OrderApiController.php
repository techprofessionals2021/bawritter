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
use Mews\Purifier\Facades\Purifier;
use App\Events\DeliveryAcceptedEvent;
use App\Events\OrderStatusChangedEvent;
use Yajra\DataTables\Facades\DataTables;
use App\Events\RequestedForRevisionEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
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

        $query = Order::with([
            'assignee',
            'customer'
        ])->where('customer_id', $request->id);

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

        $orders = $query->get();

        return apiResponseSuccess(['data' => $orders], 'Successful!');
    }


    function show($id)
    {
        $data = Order::find($id);

        return apiResponseSuccess($data, 'order detail');
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

        return apiResponseSuccess($data, 'order has been stored');

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

        $data = Storage::disk('local')->url($request->file);
        return apiResponseSuccess($data, 'Attachment url');

    }

}

