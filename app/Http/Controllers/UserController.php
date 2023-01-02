<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function payments(){
        $payments = Auth::user()->payments()->get();
        return view('user.payments', ['payments' => $payments]);
    }

    public function account(){
        if (Auth::check()){
            $account = Auth::user()->account;
        } else {
            $account = null;
        }
        return view('user.account', ['account' => $account]);

    }

}
