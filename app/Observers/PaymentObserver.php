<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */

    public function saving(Payment $payment){
        $bytes = Str::random(36);
        $link = url('/payment/'.$bytes);
        $payment->link = $link;

    }
    public function created(Payment $payment)
    {
        //
    }

    public function updating(Payment $payment){
        //TODO: Research how to get $request from blade on observer if there is no authenticated user.
        //TODO: $transaction->from = $request->email, $transaction->via = "credit card"
        if ($payment->isDirty('counter')){
            $transaction = new Transaction();
            $transaction->amount = $payment->amount_per_person ;
            $transaction->from = request()->has('from') ? request()->get('from') : Auth::id();
            $transaction->to = $payment->user_id;
            $transaction->via = Auth::check() ? 'wallet' : 'credit card';
            $transaction->save();
        }

    }

    /**
     * Handle the Payment "updated" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function updated(Payment $payment)
    {

    }

    /**
     * Handle the Payment "deleted" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function deleted(Payment $payment)
    {
        //
    }

    /**
     * Handle the Payment "restored" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function restored(Payment $payment)
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function forceDeleted(Payment $payment)
    {
        //
    }
}
