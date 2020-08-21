<?php

namespace App\Http\Controllers;

use App\Services\ApplicationService;
use App\Services\MeetingService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    protected $userService;
    protected $meetingService;
    protected $applicationService;

    function __construct(UserService $userService, MeetingService $meetingService, ApplicationService $applicationService) {
        $this->userService = $userService;
        $this->meetingService = $meetingService;
        $this->applicationService = $applicationService;
    }

    public function get(Request $request) {

        $user = $request->user();
        $meetings = $this->meetingService->findByFounder($user->id);
        $user_apps = $this->applicationService->findUserApplications($user->id);

        return view('Profile.profile',[
            'user' =>$user,
            'meetings' => $meetings,
            'user_apps' => $user_apps,
        ]);
    }

    public function fix(Request $request) {
        $validator = $request->validate([
            'username' => ['bail','required','exists:users,username'],
            'password' => ['required'],
            'email' => ['required']
        ]);

        $user = $request->user();

        return view('Profile.modifyProfile',['user'=>$user]);
    }

    public function update(Request $request) {
        $validator = $request->validate([
            'username' => ['bail','required','max:30'],
            'email' => ['bail','required','email'],
            'name' => ['required','max:30'],
            'password' => ['required','confirmed'],
            'password_confirmation' => ['required'],
        ]);

        $user = $this->userService->update($request);
        if ($user == null) {
            // TODO : Alert Fail
            return redirect('/home');
        }
        Auth::logout();

        return redirect('/home');
    }



}
