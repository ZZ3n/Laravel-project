<?php

namespace App\Http\Controllers;

use App\Meeting;
use Illuminate\Http\Request;
use App\User;
use App\Group;

class MeetingController extends Controller
{
    public function meetings(Request $request) {
        return view('meetings.list');
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
            $meeting = new Meeting;
            $meeting->name = $request->name;
            $meeting->founder_id = $request->session()->get('uid');
            $meeting->content = $request->meeting_content;
            $meeting->save();
            $group = new Group;
            $group->name = $request->gName;
            $group->meeting_id = $meeting->id;
            $group->apply_start_date = $request->apply_start_date;
            $group->apply_end_date = $request->apply_end_date;
            $group->act_start_date = $request->action_start_date;
            $group->act_end_date = $request->action_end_date;
            $group->capacity = $request->capacity;
            $group->approval_opt = $request->apv_opt;
            $group->save();

//
    }
}
