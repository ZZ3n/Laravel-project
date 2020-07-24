<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\User;
use App\UserServiceInterface;
use Illuminate\Http\Request;
use App\Repositories\UserRepositoryImpl;
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
        if (!$this->userRepository->checkPassword($user->id,$request->password)) { //login failed
            return false;
        }

        return $user;
    }
}
