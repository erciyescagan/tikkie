<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function received(){
        return Auth::guard('api')->user()->received()->get();
    }

    public function expended(){
        return Auth::guard('api')->user()->expended()->get();
    }

    public function totalReceived(){
        return Auth::guard('api')->user()->received()->sum('amount');
    }

    public function totalExpended(){
        return Auth::guard('api')->user()->expended()->sum('amount');
    }

    public function receivedViaCreditCard(){
        return Auth::guard('api')->user()->received()->where('via', 'credit card')->get();
    }

    public function receivedViaWallet(){
        return Auth::guard('api')->user()->received()->where('via', 'wallet')->get();
    }

    public function expendedViaCreditCard(){
        return Auth::guard('api')->user()->expended()->where('via', 'credit card')->get();
    }

    public function expendedViaWallet(){
        return Auth::guard('api')->user()->expended()->where('via', 'wallet')->get();
    }


}
