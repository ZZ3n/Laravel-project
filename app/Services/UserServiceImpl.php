<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\User;
use App\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserServiceImpl implements UserService {

    private $userRepository;

    function __construct(UserRepository $userRepository) {
        $this->userRepository =$userRepository;
    }

    public function register(Request $request)
    {
        $user = User::fromRequest($request);
        $this->userRepository->store($user);
    }

    public function login(Request $request)
    {
        $user = $this->userRepository->findByUsername($request->username);
        if (null == $this->userRepository->checkPassword($user->id,$request->password)) { //login failed
            return false;
        }

        return $user;
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
        $user = $this->userRepository->findByUsername($request->session()->get('username'));
        $check = collect();
        if ($request->username != $user->username)
            $check->push($this->userRepository->updateUsername($user->id,$request->username));
        if (Hash::check($request->password,$user->password) == false)
            $check->push($this->userRepository->updatePassword($user->id,$request->password));
        if ($request->name != $user->name)
            $check->push($this->userRepository->updateName($user->id,$request->name));
        if ($request->email != $user->email)
            $check->push($this->userRepository->updateEmail($user->id,$request->email));
        return $user;
    }

}
