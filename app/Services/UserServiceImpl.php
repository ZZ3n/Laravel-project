<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\User;
use App\UserServiceInterface;
use Illuminate\Http\Request;

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
        $user = $this->userRepository->findByUsername($request->session('username'));
        $this->userRepository->updateUsername($user->id,$request->username);
        $this->userRepository->updatePassword($user->id,$request->password);
        $this->userRepository->updateName($user->id,$request->name);
        $this->userRepository->updateEmail($user->id,$request->email);

        return $user;
    }

}
