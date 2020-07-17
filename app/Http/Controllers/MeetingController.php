<?php

namespace App\Http\Controllers;

use App\Meeting;
use Illuminate\Http\Request;
use App\User;
use App\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MeetingController extends Controller
{
    public function meetings(Request $request)
    {
        // 모임의 데이터를 배열로 만들어서 전달.
        $meetings = DB::table('groups')
            ->leftJoin('meetings', 'meetings.id', '=', 'groups.meeting_id')
            ->select(DB::raw('meeting_id,max(act_end_date) as act_end_date,meetings.name,meetings.content'))
            ->groupBy('meeting_id')
            ->get();
        //ddd($meetings);
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
        foreach($groups as $group_string) {
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

    public function detail(Request $request, $meetingId = null) {
        if ($meetingId==null) {
            return redirect('/meetings');
        }
        $meeting = Meeting::where('id',$meetingId)->get()->first();
        $founder = User::where('id',$meeting->founder_id)->get()->first();
        $groups = Group::where('meeting_id',$meeting->id)->get();
//        ddd($meeting->name,$founder->username,$meeting->content);
//        $detail = collect([
//            'meetingName' => $meeting->name,
//            'meetingId' => $meeting->id,
//            'founderName' => $founder->username,
//            'meetingContent' => $meeting->content,
//            'founderEmail' => $founder->email
//        ]);
        return view('meetings.detail')->with([
            'meeting' => $meeting,
            'group_list' => $groups,
            'founder' => $founder
        ]);
    }

    public function apply(Request $request, $meetingId = null, $groupId = null) {
        $meeting = Meeting::where('id',$meetingId)->get()->first();
        $group = Group::where('meeting_id',$meetingId)->where('id',$groupId)->get()->first();
        $founder = User::where('id',$meeting->founder_id)->get()->first();
        if ($meeting == null | $group == null) {
            return redirect('/home');
        }
        return view('meetings.apply')->with([
            'meeting' => $meeting,
            'group' => $group,
            'founder' => $founder,
        ]);
    }
}
