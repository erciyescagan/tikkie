<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WithdrawController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('/login', [ApiAuthController::class, 'login'])->name('login.api');
    Route::post('/register',[ApiAuthController::class, 'register'])->name('register.api');
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout.api');
});

Route::group(['middleware' => ['auth:api', 'cors', 'json.response']], function (){
    Route::post('/payment/store', [PaymentController::class, 'store']);
    Route::post('/account/update', [AccountController::class, 'update']);
    Route::post('/deposit', [DepositController::class, 'store']);
    Route::get('/received', [TransactionController::class, 'received']);
    Route::get('/expended', [TransactionController::class, 'expended']);
    Route::get('/receivedViaCreditCard', [TransactionController::class, 'receivedViaCreditCard']);
    Route::get('/receivedViaWallet', [TransactionController::class, 'receivedViaWallet']);
    Route::get('/expendedViaCreditCard', [TransactionController::class, 'expendedViaCreditCard']);
    Route::get('/expendedViaWallet', [TransactionController::class, 'expendedViaWallet']);
    Route::post('/withdraw', [WithdrawController::class, 'store'])->name('withdraw');
    Route::post('/deposit', [DepositController::class, 'store'])->name('deposit');
    Route::post('/contact/add', [ContactController::class, 'store']);
    Route::get('/contacts', [ContactController::class, 'index']);
});
