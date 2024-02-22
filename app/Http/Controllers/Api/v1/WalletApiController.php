<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\models\Payment;
use App\models\Setting;
use Illuminate\Http\Request;
use App\models\Tag;
use App\models\User;
use App\models\Wallet;
use App\models\WalletTransaction;

class WalletApiController extends Controller
{

public function index(Request $request)
{
    $user_id = $request->id;

    $wallet=Wallet::where('user_id',$user_id)->first();

    $balance = $wallet->balance;

    $formattedBalance = format_money($balance);
    // $data = [
    //     'balance' => $formattedBalance,
    // ];

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
   }else{
     return responseError('Data not received');
    }


}



}
