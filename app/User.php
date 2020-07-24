<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

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

    public static function fromRequest(Request $request) {
        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        return $user;
    }
}
