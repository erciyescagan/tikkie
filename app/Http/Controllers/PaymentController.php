<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PaymentController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        return view('payment.create');
    }


    public function store(Request $request)
    {
        $requestArr = [
            'amount'=> $request->amount,
            'from' => Auth::user()->email,
        ];

        $payment = new Payment();
        $payment->amount = $request->amount;
        $payment->link = env('APP_URL').'/payment/'.base64_encode(json_encode($requestArr));
        $payment->status = 'request';
        $payment->expiration_date = null;
        $payment->is_expired = 0;
        $payment->is_valid = 0;
        if ($payment->save()){
            Auth::user()->payments()->attach($payment->id);
        }
    }


    public function request($hash)
    {
        $info = json_decode(base64_decode($hash));
        return view('payment.pay', ['info' => $info]);
    }

    public function pay(Request $request){
        $from = json_decode(base64_decode($request->hash))->from;
        $payments = User::where('email', $from)->first()->payments()->get();
        $payment = $payments->where('link', $request->hash)->first();
        $payee = $payment->user->account;
        $payer = Auth::user()->account;

        $payee->wallet_balance = (float)$payee->wallet_balance + (float)$payment->amount;
        $payee->save();

        $payer->wallet_balance = (float)$payer->wallet_balance - (float)$payment->amount;
        $payer->save();

        return redirect()->to('/my/account');

    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
