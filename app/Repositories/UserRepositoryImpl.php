<?php

namespace App\Repositories;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;



class UserRepositoryImpl implements UserRepository
{
    protected $model;

    function __construct() {
        $this->model = new User;
    }

    public function store(User $user)
    {
        $newUser = $user->save();
        return $newUser;
    }

    public function findByEmail(string $email) {
        try {
            $user = $this->model->where('email',$email)->firstOrFail();
        }catch (ModelNotFoundException $e) {
            \Illuminate\Support\Facades\Log::notice('Email Not Found');
            return false;
        }
        return $user;
    }

    public function findByUsername(string $username) {
        try {
            $user = $this->model->where('username',$username)->firstOrFail();
        }catch (ModelNotFoundException $e) {
            Log::notice('Username Not Found');
            return false;
        }
        return $user;
    }

    public function findById(int $id) {
        try {
            $user = $this->model->where('id',$id)->firstOrFail();
        }catch (ModelNotFoundException $e) {
            Log::notice('ID Not Found');
            return false;
        }
        return $user;
    }

    public function updateEmail(int $id, string $email) {
        $user = $this->findById($id);
        if (!$user) { // ID invalid
            return false;
        }
        if (!$this->findByEmail($email)) {// email Exist
            return false;
        }
        $user->email = $email;
        $user->save();

        return $user;
    }

    public function updateUsername(int $id, string $username) {
        $user = $this->findById($id);
        if (!$user) { // ID invalid
            return false;
        }
        if (!$this->findByUsername($username)) { // username exist
            return false;
        }
        $user->username = $username;
        $user->save();

        return $user;
    }
    public function updatePassword(int $id, string $password) {
        $user = $this->findById($id);
        if (!$user) { // ID invalid
            return false;
        }
        $user->password = Hash::make($password);
        $user->save();

        return $user;
    }
    public function updateName(int $id, string $name) {
        $user = $this->findById($id);
        if (!$user) { // ID invalid
            return false;
        }
        $user->name = $name;
        $user->save();

        return $user;
    }

    public function checkPassword(int $id, string $password) {
        $user = $this->findById($id);

        if (!$user) {
            return false;
        }
        if ( !Hash::check($password,$user->password)) {
            return false;
        }
        return true;
    }
}