<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserServiceImpl implements UserService
{

    private $userRepository;

    function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        $user = User::fromRequest($request);
        DB::transaction(function () use ($user) {
            $this->userRepository->store($user);
        }, 5);

    }

    public function login(Request $request)
    {
        $credentials = $request->only('username','password');
        if (Auth::attempt($credentials)) {
            $user = $this->userRepository->findByUsername($request->username);
            return $user;
        }
        return false;
//        if (null == $this->userRepository->checkPassword($user->id, $request->password)) { //login failed
//            return false;
//        }


    }

    public function findById(int $id)
    {
        $user = $this->userRepository->findById($id);
        return $user;
    }

    public function findByUsername(string $username)
    {
        $user = $this->userRepository->findByUsername($username);

        return $user;
    }

    public function update(Request $request)
    {
        $user = $request->user();
        try {
            DB::beginTransaction();
            if ($request->username != $user->username)
                $this->userRepository->updateUsername($user->id, $request->username);
            if (Hash::check($request->password, $user->password) == false)
                $this->userRepository->updatePassword($user->id, $request->password);
            if ($request->name != $user->name)
                $this->userRepository->updateName($user->id, $request->name);
            if ($request->email != $user->email)
                $this->userRepository->updateEmail($user->id, $request->email);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //TODO:: 예외상황 발생 알리기!
            return null;
        }

        return $user;
    }

}
