<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'username' => 'string',
        'name' => 'string',
    ];

    public function meetings() {
        //미팅을 소유하고 있음.
        return $this->hasMany('App\Meeting');
    }

    public function applications() {
        //application을 소유하고 있음.
        return $this->hasMany('App\Application');
    }

}
