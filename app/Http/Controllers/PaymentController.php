<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private $json, $user, $link;

    public function __construct(Request $request)
    {
        $this->setJson($request);
        $this->user = $request->user();
    }

    public function store(){
        $user = $this->user;

        $payment = new Payment();
        $payment->amount = $this->getAmount();
        $payment->link = $this->getLink();
        $payment->status = 'request';
        $payment->expiration_date = null;
        $payment->type = $this->getType();
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

    private function setJson($request){$this->json = $request->all();}

    private function getJson() : array {return $this->json;}

    private function setAmount(){$this->amount = $this->getJson()['amount'];}

    private function getAmount() : float {$this->setAmount();return $this->amount;}

    private function setType(){$this->type = $this->getJson()['type'];}

    private function getType() : int {$this->setType();return $this->type;}

    private function setLink()  {
        $bytes = Str::random(36);
        $this->link = url('/payments/'.$bytes);
    }

    private function getLink() :
    \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\UrlGenerator|string
    {$this->setLink(); return $this->link;}

    private function calculateAmount(){
        //TODO: amount calculator according to payment type.
    }

}
