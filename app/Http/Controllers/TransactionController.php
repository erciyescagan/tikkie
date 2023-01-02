<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function received(Request $request){
        return $request->user()->received()->get();
    }

    public function expended(Request $request){
        return $request->user()->expended()->get();
    }

    public function totalReceived(Request $request){
        return $request->user()->received()->sum('amount');
    }

    public function totalExpended(Request $request){
        return $request->user()->expended()->sum('amount');
    }

    public function receivedViaCreditCard(Request $request){
        return $request->user()->received()->where('via', 'credit card')->get();
    }

    public function receivedViaWallet(Request $request){
        return $request->user()->received()->where('via', 'wallet')->get();
    }

    public function expendedViaCreditCard(Request $request){
        return $request->user()->expended()->where('via', 'credit card')->get();
    }

    public function expendedViaWallet(Request $request){
        return $request->user()->expended()->where('via', 'wallet')->get();
    }

}
