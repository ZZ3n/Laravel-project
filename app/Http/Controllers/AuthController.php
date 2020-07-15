<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function register() {
        return view('register');
    }

    public function tryRegister(Request $request) {
//       $validator = $request->validate([
//            'username' => 'bail | required | unique:users,username',
//            'email' => 'bail | required | unique:users,email',
//            'name' => 'required',
//            'password' => 'required | confirmed',
//            'password_confirmation' => 'required'
//        ]);
        $user = new User;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        ddd($request->password,$user->password);
        if ( ! Hash::check($request->password_confirmation,$user->password )) {
            // 알림 구현해야 함.
            return back();
        }
        $user->save();
        return redirect('/login');
    }

    public function login() {
        return view('login');
    }


    public function tryLogin(Request $request) {
        $request->password = Hash::make($request->password);
        $user = User::where('username',$request->username)->get()->first();

//        $validator = $request->validate([
//            'username' => 'same:username',
////                'bail | required | exists:users,username',
//            'password' => 'same:'.$user->password,
//        ]);

        ddd($request->password,$user->password);
        if ($request->password == $user->password) { // login success! // 두번 체크하는 것임. 교체 필요.
            $request->session()->put([
                'is_login' => true,
                'uid' => $user->id,
                'username' => $user->username,
            ]);

            return redirect('/home');
        }
        return redirect('/login');
    }

    public function logout(Request $request) {
        $request->session()->forget(['is_login','uid']);
        return redirect('/home');
    }
}
