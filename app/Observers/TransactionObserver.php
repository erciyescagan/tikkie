<?php

namespace App\Observers;

use App\Http\Helpers\Balance;
use App\Models\Transaction;

class TransactionObserver
{
    public function created(Transaction $transaction){
        Balance::calculate($transaction);
    }
}
