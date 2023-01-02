<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Models\Account;
use Dotenv\Validator;
use Illuminate\Http\Request;
use App\Http\Helpers\Json;

class AccountController extends Controller
{
    public function update(AccountRequest $request){
        $account = $request->user()->account;
        $account->iban = $request->iban;
        $account->wallet_balance = 0;
        if($account->save())
            return response()->json(['message' => 'You have been created your wallet!'], 200);
    }

}
