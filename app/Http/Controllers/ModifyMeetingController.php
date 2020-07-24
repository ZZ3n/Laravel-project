<?php

namespace App\Http\Controllers;

use App\Application;
use App\Group;
use App\Meeting;
use App\Services\MeetingService;
use App\Services\UserService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModifyMeetingController extends Controller
{
    protected $meetingService;
    protected $userService;

    function __construct(MeetingService $meetingService, UserService $userService) {
        $this->meetingService = $meetingService;
        $this->userService = $userService;
    }

    public function getMeeting(Request $request,$meetingId = null) {
        if (!$request->session('is_login')) {
            return redirect(route('home'));
        }

        $meeting = $this->meetingService->findById($meetingId);
        $user = $this->userService->findById($request->session()->get('uid'));

        return view('meetings.modify',[
            'meeting' => $meeting,
            'user' => $user,
        ]);
    }

    public function postMeeting(Request $request,$meetingId = null) {
        //TODO : validation

        $meeting = $this->meetingService->update($meetingId,$request);

        return redirect(route('meetings').'/detail/'.$meeting->id);
    }

    public function getGroups(Request $request, $meetingId = null) {
        if (!$request->session()->has('is_login')) {
            return redirect(route('home'));
        }
        $meeting = $this->meetingService->findById($meetingId);

        $groups = Group::withCount('applications')->where('meeting_id',$meetingId)->get(); // TODO : GROUP SERVICE!
        $applications = Application::leftJoin('users','users.id','=','applications.user_id')
            ->select('users.username','applications.*')
            ->get();
        // TODO: view 단에서 모든 application 중에 if 로 골라서 출력함. 좋지 못함. 수정 필요.

        $founder = $this->userService->findById($meeting->founder_id);
        return view('meetings.modifyGroups',[
            'meeting' => $meeting,
            'groups' =>$groups,
            'founder' =>$founder,
            'applications' => $applications,
        ]);
    }

    public function acceptUser(Request $request, $meetingId=null) {
        $user = $this->userService->findByUsername($request->username);

        DB::table('applications')->where('group_id',$request->group_id)
            ->where('user_id',$user->id)->update(['approval'=>true]); // TODO: applications service!

        return back();
    }

    public function denyUser(Request $request,$meetingId = null) {
        $user = $this->userService->findByUsername($request->username);

        DB::table('applications')->where('group_id',$request->group_id)
            ->where('user_id',$user->id)->update(['approval'=>false]); // TODO: applications service!

        return back();
    }
}
