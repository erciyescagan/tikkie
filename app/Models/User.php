<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function account(){
        return $this->hasOne(Account::class, 'user_id');
    }

    public function payments(){
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function received(){
        return $this->hasMany(Transaction::class, 'to');
    }
    public function expended(){
        return $this->hasMany(Transaction::class, 'from');
    }

    public function withdraw(){
        return $this->hasMany(Withdraw::class, 'user_id');
    }

    public function deposit(){
        return $this->hasMany(Deposit::class, 'user_id');
    }

    public function contacts(){
        return $this->belongsToMany(User::class, 'user_contacts', 'user_id', 'contact_id');
    }
}
