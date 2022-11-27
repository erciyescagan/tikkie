<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private $user;

    public function __construct(Request $request){
        $this->user = $request->user();
    }

    public function received(){
        return $this->user->received()->get();
    }

    public function expended(){
        return $this->user->expended()->get();
    }

    public function totalReceived(){
        return $this->user->received()->sum('amount');
    }

    public function totalExpended(){
        return $this->user->expended()->sum('amount');
    }

    public function receivedViaCreditCard(){
        return $this->user->received()->where('via', 'credit card')->get();
    }

    public function receivedViaWallet(){
        return $this->user->received()->where('via', 'wallet')->get();
    }

    public function expendedViaCreditCard(){
        return $this->user->expended()->where('via', 'credit card')->get();
    }

    public function expendedViaWallet(){
        return $this->user->expended()->where('via', 'wallet')->get();
    }


}
