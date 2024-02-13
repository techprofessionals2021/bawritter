<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\NumberGenerator;

class PaymentRecordService
{
    /*
		PaymentRecordService records payments in payments table and the balance
        are added to the wallets table and transaction log in wallet_transactions table
	*/

    public function store($payerUserId, $paymetMethod, $amount, $transactionReference, $attachment = null)
    {
        $payment = Payment::create([
            'number' => NumberGenerator::gen('App\Models\Payment'),
            'user_id' => $payerUserId,
            'method' => $paymetMethod,
            'amount' => $amount,
            'reference' => $transactionReference,
            'attachment' => $attachment,
        ]);
        $payment->from->wallet()->deposit($payment->amount, $payment);

        return $payment;
    }
}
