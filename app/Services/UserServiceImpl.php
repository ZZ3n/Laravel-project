<?php

namespace App\Services;

use App\User;
use App\UserServiceInterface;
use Illuminate\Http\Request;
use App\Repositories\UserRepositoryImpl;
class UserServiceImpl implements UserService {

    private $userRepository;

    function __construct() {
        $this->userRepository = new UserRepositoryImpl();
    }

    public function register(Request $request)
    {
        $user = User::fromRequest($request);
        // 생성자가 중복이 되는가?
        // userRepository -> new User를 하는데, User::fromRequest도 new User를 한다.
        // Model에 Dependency가 생긴다... 좋지 못하다.
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
