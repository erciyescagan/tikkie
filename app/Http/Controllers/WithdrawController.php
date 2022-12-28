<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Balance;
use App\Http\Helpers\Json;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class WithdrawController extends Balance
{
    private $amount;


    public function __construct(Request $request)
    {
        if ($request->route()->getPrefix() == "api") {
            $request->validate([
                "amount" => "integer|required",
            ]);

            Json::setJson($request);

        }


    }

    private function setAmount(){$this->amount = Json::getJson()['amount'];}

    private function getAmount() : int {$this->setAmount();return $this->amount;}

    public function store(Request $request){
        if (Balance::check($request->user()->account->wallet_balance, $this->getAmount())){
            $withdraw = new Withdraw();
            $withdraw->amount = $this->getAmount();
            $withdraw->user_id = $request->user()->id;
            $withdraw->status = 'open';
            if ($withdraw->save()){
                return response()->json(['status' => 'success', 'statusCode' => 200,'message' => 'Para çekme talebiniz başarıyla oluşturuldu!', 'data' => ['withdraw' => $withdraw], 200]);
            }
        }
        return response()->json(['status' => 'error', 'statusCode' => 500, 'message' => 'Bakiyeniz bu işlem için yetersiz.'], 500);

    }

}
