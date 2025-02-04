<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/payment/{hash}', [PaymentController::class, 'request'])->name('payment.request');


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::post('/payment/pay', [PaymentController::class, 'pay'])->name('payment.pay');

Route::get('/my/payments', [UserController::class, 'payments'])->name('user.payments');

Route::get('/my/account', [UserController::class, 'account'])->name('user.account');


require __DIR__.'/auth.php';
