<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Json;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private int $amountPerPerson, $amount;

    public function __construct(){
        $this->amount = 0;
        $this->amountPerPerson = 0;
    }

    public function store(PaymentRequest $request)
    {
        $this->calculateAmount($request->type, $request->amount, $request->number_of_people);

        $user = $request->user();
        $payment = new Payment();
        $payment->amount = $this->amount;
        $payment->amount_per_person = $this->amountPerPerson;
        $payment->number_of_people = $request->number_of_people;
        $payment->expiration_date = null;
        $payment->type = $request->type;
        $payment->is_expired = 0;
        $payment->is_valid = 0;
        $payment->user_id = $user->id;

        $payment->save();

        return response()->json(['status' => 'success', 'statusCode' => 200, 'message' => 'İsteğiniz başarıyla oluşturuldu', 'data' => ['payment' => $payment]], 200);
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
    private function calculateAmount($type, $amount, $number_of_people = null): void
    {
        switch ($type){
            case 0 :
                $amount_per_person = $amount;
                break;
            case 1:
                return;
            case 2:
                $amount_per_person = $amount / $number_of_people;
                break;
        }
        $this->amount = $amount;
        $this->amountPerPerson = $amount_per_person;
    }

}
