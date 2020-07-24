<?php

namespace App\Services;

use Illuminate\Http\Request;

interface UserService{

    public function register(Request $request);
    public function login(Request $request);
    public function findById(int $id);
    public function findByUsername(string $username);
    public function update(Request $request);
}