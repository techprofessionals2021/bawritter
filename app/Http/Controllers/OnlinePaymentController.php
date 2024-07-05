<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\PaymentRecordService;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Services\CartService;
use App\Events\PaymentApprovedEvent;
use App\Enums\CartType;

class OnlinePaymentController extends Controller
{
    private $cart;
    private $PaymentRecordService;

    function __construct(CartService $cart, PaymentRecordService $PaymentRecordService)
    {
        $this->cart = $cart;
        $this->PaymentRecordService = $PaymentRecordService;
    }

    public function paypalPayment(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(Config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypalSuccess'),
                "cancel_url" => route('paypalCancel')
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $request->amount
                    ]
                ]
            ]
        ]);

        if(isset($response['id']) && $response['id'] != null ){
           foreach($response['links'] as $data){
                if($data['rel'] === 'approve'){
                    return redirect()->away($data['href']);
                }
           }
        } else {
            return redirect()->route('paypalCancel');
        }


    }

    public function paypalSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(Config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->token);

        if(isset($response['status']) && $response['status'] == "COMPLETED"){
            try{
                $payment = $this->PaymentRecordService->store(
                    auth()->user()->id,
                    "PayPal",
                    $this->cart->getTotal(),
                    $response['id'],
                    null,
                );

                event(new PaymentApprovedEvent($payment));

                if($this->cart->getCartType()==CartType::NewOrder){

                    $this->getOrderService()->confirmOrderPayment($this->cart->getCart()['order_id']);

                    $this->cart->destroy();

                    return redirect()->route('orders_show', $order_id)
                        ->withSuccess('Your order has been received. You will be notified when your document is ready');
                }else{
                    $this->cart->destroy();

                    return redirect()->route('my_account', ['group' => 'wallet'])
                        ->withSuccess('Your wallet has been topped up successfully');
                }


            }
            catch(\Exception  $e){
                return redirect()->route('paypalCancel');
            }
        } else {
            return redirect()->route('paypalCancel');
        }
    }

    public function paypalCancel()
    {
        return redirect()->route('pay_with_paypal')->withErrors('Payment could not be completed.');
    }

    private function getOrderService()
    {
        return app()->make('App\Services\OrderService');
    }
}
