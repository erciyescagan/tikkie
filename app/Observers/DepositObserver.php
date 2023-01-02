<?php

namespace App\Observers;

use App\Http\Helpers\Balance;
use App\Models\Deposit;
use App\Models\User;

class DepositObserver
{
    public function created(Deposit $deposit){
        $account = User::find($deposit->user_id)->account;
        $account->wallet_balance = Balance::deposit($account->wallet_balance, $deposit->amount);
        $account->save();
    }
}
