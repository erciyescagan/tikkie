<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Dotenv\Validator;
use Illuminate\Http\Request;
use App\Http\Helpers\Json;

class AccountController extends Controller
{
    private $user, $iban;
    public function __construct(Request $request)
    {
        $request->validate([
            "iban" => "starts_with:TR|required|size:26",
        ]);

        $this->user = $request->user();
        Json::setJson($request);

    }

    public function update(){
        $account = $this->user->account;
        $account->iban = $this->getIban();
        $account->wallet_balance = 0;
        $account->save();
    }

    private function setIban(){$this->iban = Json::getJson()['iban'];}

    private function getIban(){$this->setIban(); return $this->iban;}


}
