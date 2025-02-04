<?php

namespace App\Providers;

use App\Models\Deposit;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Withdraw;
use App\Observers\DepositObserver;
use App\Observers\PaymentObserver;
use App\Observers\TransactionObserver;
use App\Observers\WithdrawObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Payment::observe(PaymentObserver::class);
        Withdraw::observe(WithdrawObserver::class);
        Transaction::observe(TransactionObserver::class);
        Deposit::observe(DepositObserver::class);
    }
}
