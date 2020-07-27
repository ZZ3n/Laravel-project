<?php

namespace App\Http\Controllers;


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
        //포함해야하는것. -> max(act_end_date),meeting_name,meeting_views,meeting_created_at
        // 표시용 : act_end_date , meeting_name x, meeting_id x
        // sort용 : views x,created_at x,applied user x
        $meetings = DB::transaction(function () {
            $applications = DB::table('applications')
                ->selectRaw('count(*) as applies,group_id')
                ->groupBy('group_id');

            $groups = DB::table('groups')
                ->selectRaw('max(act_end_date) as act_end_date,meeting_id,sum(applies) as applies')
                ->leftJoinSub($applications, 'A', function ($join) {
                    $join->on('A.group_id', '=', 'groups.id');
                })->where('act_end_date', '>=', now())
                ->groupBy('meeting_id');

            $meetings = DB::table('meetings')
                ->selectRaw('meetings.id,meetings.content,meetings.name,meetings.views,meetings.created_at,G.act_end_date,applies')
                ->leftJoinSub($groups, 'G', function ($join) {
                    $join->on('meetings.id', '=', 'G.meeting_id');
                })
                ->orderBy('views', 'desc')
                ->orderBy('created_at', 'asc')->get();
            return $meetings;
        }, 5);


        return view('meetings.list', ['meetings' => $meetings]);
    }

    // meeting 생성 get 요청을 받는 컨트롤러
    public function createMeeting(Request $request)
    {
        $request->session()->forget('groups');
        $user = User::fromSession($request->session());
        if ($user == null) {
            //login 갔다가 다시 모임 개설 페이지로 오는 방법은?
            return redirect('/login')->with('loginAlert', '로그인이 필요한 서비스입니다.');
        }
        return view('meetings.create');
    }

    // meeting 생성 post 요청을 받는 컨트롤러 (meeting 생성)
    public function tryCreateMeeting(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'meeting_content' => ['required'],
        ]);
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
    public function tryCreateGroup(Request $request)
    {
//       Best : 신청 시작 ->         신청 끝
//                      행사 시작 ->        행사 끝
//                                         행사 시작 -> 행사 끝
//       특이 케이스: 행사 중에 신청을 받는 경우. -> 있을 수도 있는 상황.
//         행사 끝나고 신청을 받기 시작 하는 경우 -> 안되게 처리.
        $request->validate([
            'group_name' => ['required'],
            'apply_start_date' => ['required'],
            'apply_end_date' => ['required', 'after_or_equal:apply_start_date'],
            'action_start_date' => ['required'],
            'action_end_date' => ['required', 'after_or_equal:action_start_date', 'after_or_equal:apply_start_date'],
            'capacity' => ['required', 'max:999', 'min:1']
        ]);

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
        $uid = $request->session()->get('uid');
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

        if (!$request->session()->has('uid')) {
            return redirect('login');
        }
        $uid = $request->session()->get('uid');

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
