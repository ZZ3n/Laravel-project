<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;


class AuthController extends Controller {
    private $userService;

    function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function register() {
        return view('register');
    }

    public function tryRegister(Request $request) {
       $validator = $request->validate([
            'username' => ['bail','required','unique:users,username','max:30'],
            'email' => ['bail','required','unique:users,email','email'],
            'name' => ['required','max:30'],
            'password' => ['required','confirmed'],
            'password_confirmation' => ['required'],
       ]);

        $this->userService->register($request);

        return redirect('/login');
    }

    public function login(Request $request) {
        return view('login');
    }


    public function tryLogin(Request $request) {
        $validator = $request->validate([
            'username' => ['bail','required','exists:users,username'],
            'password' => ['required'],
        ]);

        $user = $this->userService->login($request);
        if (!$user) {
            return back()->with('loginError','패스워드가 일치하지 않습니다.');
        }

        $request->session()->put([
            'is_login' => true,
            'uid' => $user->id,
            'username' => $user->username,
        ]);
        return redirect('/home');
    }

    public function logout(Request $request) {
        $request->session()->forget(['is_login','uid','username']);
        return redirect('/home');
    }
}
