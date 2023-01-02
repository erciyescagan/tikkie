<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Json;
use App\Models\Deposit;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function __construct(Request $request){
        if ($request->route()->getPrefix() == "api") {
            $request->validate([
                "amount" => "numeric|required",
            ]);

            Json::setJson($request);

        }

    }

    public function store(Request $request){
        $deposit = new Deposit();
        $deposit->user_id = $request->user()->id;
        $deposit->amount = Json::getJson()['amount'];
        $deposit->status = 'success';
        if ($deposit->save()){
            return response()->json(['status' => 'success', 'statusCode' => 200,'message' => 'Para yatırma işleminiz başarıyla tamamlandı!', 'data' => ['deposit' => $deposit], 200]);
        }
    }
}
