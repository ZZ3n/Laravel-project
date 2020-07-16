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
    public function meetings(Request $request) {
        // 모임의 데이터를 배열로 만들어서 전달.
        $meetings = DB::table('groups')
            ->leftJoin('meetings','meetings.id','=','groups.meeting_id')
            ->select(DB::raw('meeting_id,max(act_end_date) as act_end_date,meetings.name,meetings.content'))
            ->groupBy('meeting_id')

            ->get();
        //ddd($meetings);
        return view('meetings.list',['meetings' => $meetings]);
    }

    public function createMeeting(Request $request) {
        $user = User::where('id',$request->session()->get('uid'))->get()->first();
        if ($user == null) {
            //login 갔다가 다시 모임 개설 페이지로 오는 방법은?
            return redirect('/login')->with('loginAlert','로그인이 필요한 서비스입니다.');
        }
        return view('meetings.create');
    }

    public function tryCreateMeeting(Request $request) {
        $request->validate([
            'name'=>['required'],
            'meeting_content' => ['required'],
            'group_name' =>['required'],
//       Best : 신청 시작 ->         신청 끝
//                      행사 시작 ->        행사 끝
//                                         행사 시작 -> 행사 끝
//       특이 케이스: 행사 중에 신청을 받는 경우. -> 있을 수도 있는 상황.
//         행사 끝나고 신청을 받기 시작 하는 경우 -> 안되게 처리.
            'apply_start_date' =>['required'],
            'apply_end_date' =>['required','after_or_equal:apply_start_date'],
            'action_start_date' =>['required'],
            'action_end_date' =>['required','after_or_equal:action_start_date','after_or_equal:apply_start_date'],
            'capacity' => ['required','max:999','min:1']
        ]);
            $meeting = new Meeting;
            $meeting->name = $request->name;
            $meeting->founder_id = $request->session()->get('uid');
            $meeting->content = $request->meeting_content;
            $meeting->save();

            $group = new Group;
            $group->name = $request->group_name;
            $group->meeting_id = $meeting->id;
            $group->apply_start_date = $request->apply_start_date;
            $group->apply_end_date = $request->apply_end_date;
            $group->act_start_date = $request->action_start_date;
            $group->act_end_date = $request->action_end_date;
            $group->capacity = $request->capacity;
            $group->approval_opt = $request->apv_opt;
            $group->save();
        return redirect('/home');
    }

    public function tryCreateGroup(Request $request) {

        $input = $request->all();
        //Log::debug('ajaxCall :'.$request->all());
        return response()->json(['message' => $request->json()]);
    }
}
