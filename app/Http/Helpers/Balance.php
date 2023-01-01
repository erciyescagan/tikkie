<?php

namespace App\Http\Helpers;

use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;

class Balance
{

    public static function check($payerID, $amount) : bool{
        $walletBalance = User::find($payerID)->account->wallet_balance;
        if ($walletBalance >= $amount){
            return true;
        }
        return false;
    }

    public static function withdraw($walletBalance, $amount) : int{
        return (int)$walletBalance - (int)$amount;
    }

    public static function calculate(Transaction $transaction){
        if (self::check($transaction->from, $transaction->amount)){
            self::calculateForPayer($transaction->from, $transaction);
            self::calculateForPayee($transaction->to, $transaction);
        }
    }

    public static function calculateForPayer($payerID, Transaction $transaction){
        if (is_numeric($payerID)) {
            $payer = User::find($payerID);
            $wallet_balance = $payer->account->wallet_balance;
            $wallet_balance = (float)$wallet_balance - (float)$transaction->amount;
            $payer->account->wallet_balance = $wallet_balance;
            $payer->account->save();
        }
    }

    public static function calculateForPayee($payeeID, Transaction $transaction){
        $payee = User::find($payeeID);
        $wallet_balance = $payee->account->wallet_balance;
        $wallet_balance = (float)$wallet_balance + (float)$transaction->amount;
        $payee->account->wallet_balance = $wallet_balance;
        $payee->account->save();

    }
}
