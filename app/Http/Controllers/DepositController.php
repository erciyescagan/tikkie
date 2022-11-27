<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepositController extends Controller
{
    private $user;

    public function __construct(Request $request){
        $this->user = $request->user();
    }

    public function store(){

    }
}
