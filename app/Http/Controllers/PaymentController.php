<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Json;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use \App\Http\Helpers\Payment as PaymentHelper;
class PaymentController extends PaymentHelper
{

    public function store(PaymentRequest $request)
    {
        PaymentHelper::calculateAmount($request);

        $user = $request->user();
        $payment = new Payment();
        $payment->amount = $this->amount;
        $payment->amount_per_person = $this->amountPerPerson;
        $payment->number_of_people = count($request->payers) > 0 ? count($request->payers) : $request->number_of_people;
        $payment->expiration_date = null;
        $payment->type = $request->type;
        $payment->is_expired = 0;
        $payment->is_valid = 0;
        $payment->user_id = $user->id;
        $payment->payers = count($request->payers) > 0 ? encrypt(json_encode($request->payers)) : null;

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

}
