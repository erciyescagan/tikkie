<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Json;
use App\Models\Payment;
use App\Models\User;
use http\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private $user, $link, $number_of_people, $amount, $type;

    public function __construct(Request $request)
    {
        $request->validate([
            "type" => "numeric|max:2|min:0|required_without_all:number_of_people",
            "amount" => "numeric|required",
            "number_of_people" => "nullable|numeric|min:2|required_if:type,2"
        ]);

        Json::setJson($request);
        $this->user = $request->user();
        $this->calculateAmount($this->getType());
    }

    public function store(){

        $user = $this->user;

        $payment = new Payment();
        $payment->amount = $this->amount;
        $payment->link = $this->getLink();
        $payment->status = 'request';
        $payment->expiration_date = null;
        $payment->type = $this->getType();
        $payment->is_expired = 0;
        $payment->is_valid = 0;
        $payment->user_id = $user->id;

        //$payment->save();

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


    private function setAmount(){$this->amount = Json::getJson()['amount'];}

    private function getAmount() : float {$this->setAmount();return $this->amount;}

    private function setType(){$this->type = Json::getJson()['type'];}

    private function getType() : int {$this->setType();return $this->type;}

    private function setNumberOfPeople(){$this->number_of_people = Json::getJson()['number_of_people'];}

    private function getNumberOfPeople() :int {$this->setNumberOfPeople(); return $this->number_of_people;}

    private function setLink() {$bytes = Str::random(36);$this->link = url('/payments/'.$bytes);}

    private function getLink() :
    \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\UrlGenerator|string
    {$this->setLink(); return $this->link;}

    private function calculateAmount($type): void
    {
        switch ($type){
            case 0 :
                $amount = $this->getAmount();
                break;
            case 1:
                return;
            case 2:
                $amount = $this->getAmount() / $this->getNumberOfPeople();
                break;
        }
        $this->amount = $amount;
    }


}
