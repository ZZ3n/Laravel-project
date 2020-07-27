<?php

namespace App\Http\Controllers;

use App\Services\MeetingService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Application;
use Illuminate\Support\Facades\DB;


class ProfileController extends Controller
{
    protected $userService;
    protected $meetingService;

    function __construct(UserService $userService, MeetingService $meetingService) {
        $this->userService = $userService;
        $this->meetingService = $meetingService;
    }

    public function getProfile(Request $request) {
        if ($request->session()->has('is_login') == false) {
            return redirect('/home');
        }
        $user = $this->userService->findById($request->session()->get('uid'));
        $meetings = $this->meetingService->findByFounder($user->id);

        $applications = Application::where('user_id',$user->id) // TODO : application service! findByUserId(U) with groupid
            ->leftJoin('groups','groups.id','=','applications.group_id')
            ->groupBy('groups.meeting_id')
            ->select('groups.meeting_id','applications.approval','groups.name');

        $user_apps = DB::table('meetings')
            ->rightJoinSub($applications,'AG',function($join) {
           $join->on('AG.meeting_id','=','meetings.id');
        })->select('AG.name as group_name','AG.meeting_id','meetings.id','meetings.name','AG.approval')
        ->get();

        return view('Profile.profile',[
            'user' =>$user,
            'meetings' => $meetings,
            'user_apps' => $user_apps,
        ]);
    }

    public function gotoModifyProfile(Request $request) {
        $validator = $request->validate([
            'username' => ['bail','required','exists:users,username'],
            'password' => ['required'],
            'email' => ['required']
        ]);

        $user = $this->userService->login($request);

        if ($user == false)
            return redirect('/profile');

        return view('Profile.modifyProfile',['user'=>$user]);
    }

    public function modifyProfile(Request $request) {
        $validator = $request->validate([
            'username' => ['bail','required','max:30'],
            'email' => ['bail','required','email'],
            'name' => ['required','max:30'],
            'password' => ['required','confirmed'],
            'password_confirmation' => ['required'], // TODO :: custom으로 username, email 걸러내야함.
        ]);

        $user = $this->userService->update($request);
        if ($user == null) {
            // TODO : Alert Fail
            return redirect('/home');
        }
        $request->session()->forget(['is_login','uid','username']);

        return redirect('/home');
    }



}
