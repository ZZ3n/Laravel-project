<?php

namespace App\Repositories;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\UserRepositoryInterface;


class UserRepository implements UserRepositoryInterface
{
    protected $user;

    function __construct() {
        $this->user = new User;
    }

    public function registerUser(User $user)
    {
        $newUser = $user->save();
        return $newUser;
    }

    public function findUserByEmail(string $email) {
        try {
            $user = User::where('email',$email)->firstOrFail();
        }catch (ModelNotFoundException $e) {
            \Illuminate\Support\Facades\Log::notice('Email Not Found');
            return false;
        }
        return $user;
    }

    public function findUserByUsername(string $username) {
        try {
            $user = User::where('username',$username)->firstOrFail();
        }catch (ModelNotFoundException $e) {
            Log::notice('Username Not Found');
            return false;
        }
        return $user;
    }

    public function findUserById(int $id) {
        try {
            $user = User::where('id',$id)->firstOrFail();
        }catch (ModelNotFoundException $e) {
            Log::notice('ID Not Found');
            return false;
        }
        return $user;
    }

    public function updateUserEmail(int $id, string $email) {
        $user = $this->findUserById($id);
        if (!$user) { // ID invalid
            return false;
        }
        if (!$this->findUserByEmail($email)) {// email Exist
            return false;
        }
        $user->email = $email;
        $user->save();

        return $user;
    }

    public function updateUserUsername(int $id, string $username) {
        $user = $this->findUserById($id);
        if (!$user) { // ID invalid
            return false;
        }
        if (!$this->findUserByUsername($username)) { // username exist
            return false;
        }
        $user->username = $username;
        $user->save();

        return $user;
    }
    public function updateUserPassword(int $id, string $password) {
        $user = $this->findUserById($id);
        if (!$user) { // ID invalid
            return false;
        }
        $user->password = Hash::make($password);
        $user->save();

        return $user;
    }
    public function updateUserName(int $id, string $name) {
        $user = $this->findUserById($id);
        if (!$user) { // ID invalid
            return false;
        }
        $user->name = $name;
        $user->save();

        return $user;
    }

    public function checkUserPassword(int $id, string $password) {
        $user = $this->findUserById($id);

        if (!$user) {
            return false;
        }
        if ( !Hash::check($password,$user->password)) {
            return false;
        }
        return true;
    }
}