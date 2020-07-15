<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request) {
        if ($request->session()->has('is_login') && $request->session()->get('is_login')) {
            $user = User::where('id',$request->session()->get('uid'))->get()->first();
            return view('home',['username'=>$user->username]);
        }

        return view('home');
    }
}
