<?php

namespace App\Services;

use Illuminate\Http\Request;

interface UserService{

    public function register(Request $request);
    public function login(Request $request);
}