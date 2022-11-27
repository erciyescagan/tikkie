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
}
