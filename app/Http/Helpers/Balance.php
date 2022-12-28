<?php

namespace App\Http\Helpers;

class Balance
{

    protected static function check($walletBalance, $amount) : bool{
        if ($walletBalance >= $amount){
            return true;
        }
        return false;
    }

    public static function calculate($walletBalance, $amount) : int{
        $newWalletBalance = (int)$walletBalance - (int)$amount;
        return $newWalletBalance;
    }
}
