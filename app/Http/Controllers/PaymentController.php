<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PaymentController extends Controller
{
    protected $amount;
    protected $type; // 0 => ex. Payee decide the amount, 2 => Payer decide the amount,3 => Total Amount / Number of Person

    public function __construct()
    {
        $this->amount = 0;
        $this->type = null;

    }

    public function store(Request $request){

        $this->setJson($request);

        $data = $this->getJson();
        $user = $request->user();
        $bytes = openssl_random_pseudo_bytes(16); // alternatively read from /dev/urandom

        $payment = new Payment();
        $payment->amount = $data['amount'];
        $payment->link = env('APP_URL').'/payment/'.base64_encode($bytes);
        $payment->status = 'request';
        $payment->expiration_date = null;
        $payment->type = $type;
        $payment->is_expired = 0;
        $payment->is_valid = 0;
        $payment->user_id = $user->id;

        return $payment;
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


    private function setAmount($request){
        $this->amount = $request->amount;
    }

    private function getAmount(){
        return $this->amount;
    }

    private function setType($request){
        $this->type = $request->type;
    }

    private function getType(){
        return $this->type;
    }

    private function setJson($request){
        $this->setAmount($request);
        $this->setType($request);
    }

    private function getJson(){
        $amount = $this->getAmount();
        $type = $this->getType();
        return['amount' => $amount, $type => $type];
    }

    private function calculateAmount(){
        //TODO: amount calculator according to payment type.
    }

}
