<?php

namespace App\Http\Controllers;


use App\Http\Requests\CreateGroup;
use App\Http\Requests\CreateMeeting;
use App\Meeting;
use App\Services\ApplicationService;
use App\Services\GroupService;
use App\Services\MeetingService;
use App\Services\UserService;
use App\User;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/*
 * 모임 전반을 관리하는 컨트롤러
 */

class MeetingController extends Controller
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

    // meeting list를 만드는 컨트롤러
    public function meetings(Request $request)
    {
        $meetings = $this->meetingService->sortedAll();
        return view('meetings.list', ['meetings' => $meetings]);
    }

    // meeting 생성 get 요청을 받는 컨트롤러
    public function createMeeting(Request $request)
    {
        $request->session()->forget('groups');
        return view('meetings.create');
    }

    // meeting 생성 post 요청을 받는 컨트롤러 (meeting 생성)
    public function tryCreateMeeting(CreateMeeting $request)
    {
        //세션 정보 저장하기.
        $groups = collect($request->session()->get('groups'));
        if ($groups->isEmpty()) {
            return back()->with(['groupError' => '그룹을 먼저 생성해주세요.']);
        }

        $meeting = Meeting::fromRequest($request);
        $this->meetingService->store($meeting);

        foreach ($groups as $group_string) {
            $group = Group::fromSessionJson($meeting->id, $group_string);
            $this->groupService->store($group);
        }

        //세션 정보 삭제하기.
        if ($request->session()->has('groups')) {
            $request->session()->forget('groups');
            //나중에 세션 지워졌는지 체크 해야함.
        }
        return redirect('/home');
    }

    // meeting 생성 페이지에서 Ajax 요청을 받는 컨트롤러
    public function tryCreateGroup(CreateGroup $request)
    {
        $input = collect($request->all());
        $request->session()->push('groups', json_encode($input));
        return response()->json(['group' => json_encode($input)]);
    }

    //meeting 상세 보기 페이지
    public function detail(Request $request, $meetingId = null)
    {
        if ($meetingId == null) {
            return redirect('/meetings');
        }

        $meeting = $this->meetingService->getDetail($meetingId);
        if ($meeting == null) {
            return redirect('/meetings');
        }

        $founder = $this->userService->findById($meeting->founder_id);

        $groups = Group::where('meeting_id', $meeting->id)->withCount('applications')->get();

        return view('meetings.detail')->with([
            'meeting' => $meeting,
            'group_list' => $groups,
            'founder' => $founder
        ]);
    }

    // meeting 지원 페이지를 만드는 컨트롤러
    public function apply(Request $request, $meetingId = null, $groupId = null)
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

    // meeting 지원 post요청을 받는 컨트롤러.
    public function tryApplication(Request $request)
    {
        $uid = $request->user()->id;

        if ($this->applicationService->findById($request->group_id,$uid) != null) {
            return redirect('/home'); // 신청하셨습니다.
        }
        $user = $this->userService->findById($uid);
        $group = $this->groupService->findById($request->group_id,true);

        if ($user == null || $group == null) {
            return redirect('/home'); // 유효하지 않은 요청입니다.
        }

        if ($request->approval_opt == 'first') {
            $approval = true;
        } else {
            $approval = true;
        }

        $this->applicationService->create($group->id,$uid,$request->reason,$approval);

        return redirect('/meetings/detail/' . $group->meeting_id);
//        return redirect('') 리스트 쪽으로 리다이렉팅
    }

}
