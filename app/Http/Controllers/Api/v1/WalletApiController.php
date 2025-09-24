<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Auth;

class WalletApiController extends Controller
{

public function index(Request $request)
{
    $user_id = auth()->id();

    // Find the wallet associated with the user
    $wallet = Wallet::where('user_id', $user_id)->first();

    // Check if the wallet is null
    if (is_null($wallet)) {
        return response()->json([
            'message' => 'Wallet not found',
            'status' => false
        ], 404);
    }

    // dd(Auth::user()->id);
    // Get the balance from the wallet
    $balance = $wallet->balance;

    // Format the balance
    $formattedBalance = format_money($balance);

    // Return the formatted balance in a JSON response
    return apiResponseSuccess($formattedBalance, 'Wallet current balance');


}


public function walletTransactions(Request $request)
{
    $user_id = $request->id;

    $transactions = WalletTransaction::whereHas('wallet', function ($q) use ($user_id) {

        return $q->where('user_id', $user_id);
    })->orderBy('id', 'DESC')->get();

    return apiResponseSuccess($transactions, 'Wallet transactions');
}



public function walletPayments(Request $request)
{
    $user_id = $request->id;

    $payments = Payment::whereHas('from', function ($q) use ($user_id) {

        return $q->where('user_id', $user_id);
    })->orderBy('id', 'DESC')->get();

   if($payments){
      return apiResponseSuccess($payments, 'Wallet payments');
   }
   else{
     return responseError('Data not received');
   }


}

public function payUsingWallet(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id'
    ]);
    DB::beginTransaction();

    try {
        $user = auth()->user();

        // Find order
        $order = Order::find($request->order_id);
        if (!$order) {
            return responseError([], 'Order not found', 404);
        }

        // Check order belongs to this user
        if ($order->customer_id !== $user->id) {
            return responseError([],'Unauthorized access to this order', 403);
        }

        // Find wallet
        $wallet = Wallet::where('user_id', $user->id)->first();
        if (!$wallet) {
            return responseError([], 'Wallet not found', 404);
        }

        // check if order is already paid
        if ($order->order_status_id == 1) {
            DB::rollBack();
            return responseError([],'Order already paid!', 400);
        }
        
        // Check balance
        if ($wallet->balance < $order->amount) {
            return responseError([], 'Insufficient wallet balance', 400);
        }

        // Confirm order payment (inside it pay() → deduct())
        $order = app()->make(\App\Services\OrderService::class)
            ->confirmOrderPayment($request->order_id);

        DB::commit();

        return apiResponseSuccess($order, 'Order paid successfully using wallet', 200);

    } catch (\Exception $e) {
        DB::rollBack();

        return responseError('An error occurred: ' . $e->getMessage(), 500);
    }
}

}
