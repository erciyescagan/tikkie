<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Balance;
use App\Http\Helpers\Json;
use App\Http\Requests\WithdrawRequest;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{

    public function store(WithdrawRequest $request){
        if (Balance::check($request->user()->id, $request->amount)){
            $withdraw = new Withdraw();
            $withdraw->amount = $request->amount;
            $withdraw->user_id = $request->user()->id;
            $withdraw->status = 'open';
            if ($withdraw->save()){
                return response()->json(['status' => 'success', 'statusCode' => 200,'message' => 'Para çekme talebiniz başarıyla oluşturuldu!', 'data' => ['withdraw' => $withdraw]], 200);
            }
        }
        return response()->json(['status' => 'error', 'statusCode' => 500, 'message' => 'Bakiyeniz bu işlem için yetersiz.'], 500);

    }

}
