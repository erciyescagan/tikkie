<?php

namespace App\Observers;

use App\Http\Helpers\Balance;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\PaymentNotification;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */

    public function creating(Payment $payment){
            $bytes = Str::random(36);
            $link = url('/payment/'.$bytes);
            $payment->link = $link;

    }
    public function created(Payment $payment)
    {
        if (!is_null($payment->payers)){
            $payers = json_decode(decrypt($payment->payers), true);
            foreach ($payers as $payer){
                Notification::sendNow(User::find($payer), new PaymentNotification($payment, User::find($payment->user_id)));

            }
            $payment->save();
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function updating(Payment $payment){
        //TODO: Should get request if request has related keys like 'from' or 'user_id'
        if ($payment->isDirty('counter')){
            $transaction = new Transaction();
            $transaction->amount = $payment->amount_per_person;
            $transaction->from = !is_null(request()->get('from')) ? request()->get('from') : auth()->guard('web')->id();
            $transaction->to = $payment->user_id;
            $transaction->via = Auth::check() ? 'wallet' : 'credit card';
            $transaction->payment_id = $payment->id;
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
