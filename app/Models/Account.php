<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;


    public static function createEmptyAccount($user_id){
        $account = new Account();
        $account->user_id = $user_id;
        $account->save();
    }
}
