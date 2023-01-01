<?php

namespace App\Observers;

use App\Http\Helpers\Balance;
use App\Models\User;
use App\Models\Withdraw;

class  WithdrawObserver
{
    public function created(Withdraw $withdraw)
    {
        $user = User::findOrFail($withdraw->user_id);
        $account = $user->account;
        $account->wallet_balance = Balance::withdraw($user->account->wallet_balance, $withdraw->amount);
        $user->account->save();
    }
}
