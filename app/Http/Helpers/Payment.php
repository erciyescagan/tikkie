<?php

namespace App\Http\Helpers;

use App\Models\User;

class Payment
{
    protected float $amount, $amountPerPerson;
    public function __construct(){
        $this->amount = 0;
        $this->amountPerPerson = 0;
    }

    protected function calculateAmount($request) : void
    {
        switch ($request->type){
            case 0 :
                $amountPerPerson = $request->amount;
                break;
            case 1:
                return;
            case 2:
                $amountPerPerson = $request->amount / $request->number_of_people;
                break;
            case 3:
                $this->selectedPayersCheck($request);
                $payersCount = count($request->payers);
                $amountPerPerson = $request->amount / $payersCount;
                break;
        }
        $this->amount = $request->amount;
        $this->amountPerPerson = $amountPerPerson;
    }

    protected function selectedPayersCheck($request){
        $IDsNotFound = [];
        foreach ($request->payers as $payer){
            if (!User::find($payer)){
                $IDsNotFound[] = $payer;
            }
        }
        if (count($IDsNotFound) > 0){
            abort(500, json_encode(['status' => 'error', 'statusCode' => 500, 'message' => 'Seçtiğiniz bazı kullanıcılarla ilgili bir hata oluştu lütfen iletişime geçin!', 'data' => $IDsNotFound]));
        }
    }
}
