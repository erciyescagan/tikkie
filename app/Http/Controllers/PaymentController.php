<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Json;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private $user, $number_of_people, $amount, $type, $amount_per_person;

    public function __construct(Request $request)
    {
        if ($request->route()->getPrefix() == "api") {
            $request->validate([
                "type" => "numeric|max:2|min:0|required_without_all:number_of_people",
                "amount" => "numeric|required",
                "number_of_people" => "nullable|numeric|min:2|required_if:type,2"
            ]);

            Json::setJson($request);
            $this->user = $request->user();
            $this->calculateAmount($this->getType());
        }


    }

    public function store()
    {

        $user = $this->user;

        $payment = new Payment();
        $payment->amount = $this->amount;
        $payment->amount_per_person = $this->amount_per_person;
        $payment->number_of_people = $this->number_of_people;
        $payment->expiration_date = null;
        $payment->type = $this->getType();
        $payment->is_expired = 0;
        $payment->is_valid = 0;
        $payment->user_id = $user->id;

        $payment->save();

        return $payment;
    }

    public function request($hash)
    {
        $payment = Payment::where('link', 'like', '%'.$hash.'%')->first();
        return view('payment.pay', ['payment' => $payment]);
    }

    public function pay(Request $request){
        $payment = Payment::where('link', 'like', '%'.$request->hash.'%')->first();
        $payment->counter += 1;
        $payment->save();


        return redirect()->to('/my/account');

    }


    private function setAmount(){$this->amount = Json::getJson()['amount'];}

    private function getAmount() : float {$this->setAmount();return $this->amount;}

    private function setType(){$this->type = Json::getJson()['type'];}

    private function getType() : int {$this->setType();return $this->type;}

    private function setNumberOfPeople(){$this->number_of_people = Json::getJson()['number_of_people'];}

    private function getNumberOfPeople() :int {$this->setNumberOfPeople(); return $this->number_of_people;}

    private function calculateAmount($type): void
    {
        switch ($type){
            case 0 :
                $amount = $this->getAmount();
                $amount_per_person = $amount;
                break;
            case 1:
                return;
            case 2:
                $amount = $this->getAmount();
                $amount_per_person = $this->getAmount() / $this->getNumberOfPeople();
                break;
        }
        $this->amount = $amount;
        $this->amount_per_person = $amount_per_person;
    }


}
