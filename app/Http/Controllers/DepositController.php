<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Json;
use App\Http\Requests\DepositRequest;
use App\Models\Deposit;
use Illuminate\Http\Request;

class DepositController extends Controller
{

    public function store(DepositRequest $request){
        $deposit = new Deposit();
        $deposit->user_id = $request->user()->id;
        $deposit->amount = $request->amount;
        $deposit->status = 'success';
        if ($deposit->save()){
            return response()->json(['status' => 'success', 'statusCode' => 200,'message' => 'Para yatırma işleminiz başarıyla tamamlandı!', 'data' => ['deposit' => $deposit], 200]);
        }
    }
}
