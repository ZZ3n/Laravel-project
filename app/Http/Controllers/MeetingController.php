<?php

namespace App\Http\Controllers;

use App\Application;
use App\Meeting;
use App\User;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{

    public function meetings(Request $request)
    {
        //포함해야하는것. -> max(act_end_date),meeting_name,meeting_views,meeting_created_at
        // 표시용 : act_end_date , meeting_name x, meeting_id x
        // sort용 : views x,created_at x,applied user x
        $meetings = DB::transaction( function() {
            $applications = DB::table('applications')
                ->selectRaw('count(*) as applies,group_id')
                ->groupBy('group_id');

            $groups = DB::table('groups')
                ->selectRaw('max(act_end_date) as act_end_date,meeting_id,sum(applies) as applies')
                ->leftJoinSub($applications,'A',function($join) {
                    $join->on('A.group_id','=','groups.id');
                })->where('act_end_date','>=',now())
                ->groupBy('meeting_id');

            $meetings = DB::table('meetings')
                ->selectRaw('meetings.id,meetings.content,meetings.name,meetings.views,meetings.created_at,G.act_end_date,applies')
                ->leftJoinSub($groups,'G',function($join) {
                    $join->on('meetings.id','=','G.meeting_id');
                })
                ->orderBy('views','desc')
                ->orderBy('created_at','asc')->get();
            return $meetings;
        }, 5);


        return view('meetings.list', ['meetings' => $meetings]);
    }

    public function createMeeting(Request $request)
    {
        $request->session()->forget('groups');
        $user = User::where('id', $request->session()->get('uid'))->get()->first();
        if ($user == null) {
            //login 갔다가 다시 모임 개설 페이지로 오는 방법은?
            return redirect('/login')->with('loginAlert', '로그인이 필요한 서비스입니다.');
        }
        return view('meetings.create');
    }

    public function tryCreateMeeting(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'meeting_content' => ['required'],
        ]);

        $meeting = new Meeting;
        $meeting->name = $request->name;
        $meeting->founder_id = $request->session()->get('uid');
        $meeting->content = $request->meeting_content;
        $meeting->save();

        //세션 정보 저장하기.
        $groups = collect($request->session()->get('groups'));
        foreach ($groups as $group_string) {
            $info = json_decode($group_string);
            $group = new Group;
            $group->name = $info->group_name;
            $group->capacity = $info->capacity;
            $group->apply_start_date = $info->apply_start_date;
            $group->apply_end_date = $info->apply_end_date;
            $group->act_start_date = $info->action_start_date;
            $group->act_end_date = $info->action_end_date;
            $group->approval_opt = $info->apv_opt;
            $group->meeting_id = $meeting->id;
            $group->save();
        }

        //세션 정보 삭제하기.
        if ($request->session()->has('groups')) {
            $request->session()->forget('groups');
            //나중에 세션 지워졌는지 체크 해야함.
        }
        return redirect('/home');
    }

    public function tryCreateGroup(Request $request)
    {

        $request->validate([
            'group_name' => ['required'],
//       Best : 신청 시작 ->         신청 끝
//                      행사 시작 ->        행사 끝
//                                         행사 시작 -> 행사 끝
//       특이 케이스: 행사 중에 신청을 받는 경우. -> 있을 수도 있는 상황.
//         행사 끝나고 신청을 받기 시작 하는 경우 -> 안되게 처리.
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

    public function detail(Request $request, $meetingId = null)
    {
        if ($meetingId == null) {
            return redirect('/meetings');
        }
        $meeting = Meeting::where('id', $meetingId)->get()->first();
        if ($meeting == null) {
            return redirect('/meetings');
        }
        $meeting->views = $meeting->views + 1;
        $meeting->save();
        $founder = User::where('id', $meeting->founder_id)->get()->first();
        $groups = Group::where('meeting_id', $meeting->id)->withCount('applications')->get();
        return view('meetings.detail')->with([
            'meeting' => $meeting,
            'group_list' => $groups,
            'founder' => $founder
        ]);
    }

    public function apply(Request $request, $meetingId = null, $groupId = null)
    {
        $meeting = Meeting::where('id', $meetingId)->get()->first();
        $group = Group::where('id', $groupId)->withCount('applications')->first();
        $founder = User::where('id', $meeting->founder_id)->get()->first();
        if ($meeting == null | $group == null) {
            return redirect('/home');
        }
        $already = Application::where('group_id', $groupId)->
        where('user_id', $request->session()->get('uid'))->first();
        if ($already != null) {
            $request->session()->flash('already', true);
        }
        return view('meetings.apply')->with([
            'meeting' => $meeting,
            'group' => $group,
            'founder' => $founder,
        ]);
    }

    public function tryApplication(Request $request)
    {
        if (!$request->session()->has('uid')) {
            return redirect('login');
        }
        if (Application::where('group_id', $request->group_id)->
            where('user_id', $request->session()->get('uid'))->first() != null
        ) {
            return redirect('/home'); // 신청하셨습니다.
        }
        $user = User::where('id', $request->session()->get('uid'))->first();
        $group = Group::where('id', $request->group_id)->withCount('applications')->first();
        if ($user == null || $group == null) {
            return redirect('/home'); // 유효하지 않은 요청입니다.
        }

        $apply = new Application;
        $apply->user_id = $user->id;
        $apply->group_id = $group->id;
        $apply->reason = $request->reason;
        if ($request->approval_opt == 'first') {
            $apply->approval = true;
        } else {
            $apply->approval = false;
        }
        $apply->save();
        return redirect('/meetings/detail/'.$group->meeting_id);
//        return redirect('') 리스트 쪽으로 리다이렉팅
    }

}
