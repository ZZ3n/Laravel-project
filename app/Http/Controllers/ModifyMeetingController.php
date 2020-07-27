<?php

namespace App\Http\Controllers;

use App\Application;
use App\Group;
use App\Services\ApplicationService;
use App\Services\GroupService;
use App\Services\MeetingService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModifyMeetingController extends Controller
{
    protected $meetingService;
    protected $userService;
    protected $groupService;
    protected $applicationService;
    function __construct(MeetingService $meetingService, UserService $userService,
        GroupService $groupService,ApplicationService $applicationService) {
        $this->meetingService = $meetingService;
        $this->userService = $userService;
        $this->groupService = $groupService;
        $this->applicationService = $applicationService;
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
        $groups = $this->groupService->findByMeetingId($meetingId,true);
        $applications = $this->applicationService->findMeetingApplications($meetingId); // TODO: view 단에서 meeting의 application 중에 if 로 골라서 출력함. 좋지 못함. 수정 필요.

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
        $this->applicationService->approval($request->group_id,$user->id);

        return back();
    }

    public function denyUser(Request $request,$meetingId = null) {

        $user = $this->userService->findByUsername($request->username);
        $this->applicationService->deny($request->group_id,$user->id);

        return back();
    }
}
