<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUser;
use App\Http\Requests\RegisterUser;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller {
    private $userService;

    function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function register() {
        return view('register');
    }

    public function tryRegister(RegisterUser $request) {
        $this->userService->register($request);

        return redirect('/login');
    }

    public function login(Request $request) {
        return view('login');
    }


    public function tryLogin(LoginUser $request) {
        $user = $this->userService->login($request);
        if (!$user) {
            return back()->with('loginError','패스워드가 일치하지 않습니다.');
        }
        return redirect('/home');
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/home');
    }
}
