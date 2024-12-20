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



}
