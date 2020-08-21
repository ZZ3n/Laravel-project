<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGroup;
use App\Services\ApplicationService;
use App\Services\GroupService;
use App\Services\MeetingService;
use App\Services\UserService;
use Illuminate\Http\Request;

class GroupController extends Controller
{

    protected $meetingService;
    protected $userService;
    protected $groupService;
    protected $applicationService;

    function __construct(MeetingService $meetingService, UserService $userService,
                         GroupService $groupService, ApplicationService $applicationService)
    {
        $this->meetingService = $meetingService;
        $this->userService = $userService;
        $this->groupService = $groupService;
        $this->applicationService = $applicationService;
    }

    // meeting 생성 페이지에서 Ajax 요청을 받는 컨트롤러
    public function build(CreateGroup $request)
    {
        $input = collect($request->all());
        $request->session()->push('groups', json_encode($input));
        return response()->json(['group' => json_encode($input)]);
    }

    // meeting detail 에서 그룹 선택했을때의 화면을 맡는 컨트롤러
    public function select(Request $request, $meetingId = null, $groupId = null)
    {
        $meeting = $this->meetingService->findById($meetingId);
        $group = $this->groupService->findById($groupId,true);
        $founder = $this->userService->findById($meeting->founder_id);

        if ($meeting == null | $group == null) {
            return redirect('/home');
        }
        $uid = $request->user()->id;
        $already = $this->applicationService->findById($groupId,$uid);

        if ($already != null) {
            $request->session()->flash('already', true);
        }

        return view('meetings.apply')->with([
            'meeting' => $meeting,
            'group' => $group,
            'founder' => $founder,
        ]);
    }

    public function manage(Request $request, $meetingId = null) {

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

}
