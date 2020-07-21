<?php

namespace App\Http\Controllers;

use App\Application;
use App\Group;
use App\Meeting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModifyMeetingController extends Controller
{
    public function getMeeting(Request $request,$meetingId = null) {
        if (!$request->session()->has('is_login')) {
            return redirect(route('home'));
        }
        $meeting = Meeting::where('id',$meetingId)->first();
        $user = User::where('id',$request->session()->get('uid'))->first();

        return view('meetings.modify',[
            'meeting' => $meeting,
            'user' => $user,
        ]);
    }

    public function postMeeting(Request $request,$meetingId = null) {

        $meeting = Meeting::where('id',$meetingId)->first();
        $meeting->name = $request->name;
        $meeting->content = $request->meeting_content;
        $meeting->save();
        return redirect(route('meetings').'/detail/'.$meeting->id);
    }

    public function getGroups(Request $request, $meetingId = null) {
        if (!$request->session()->has('is_login')) {
            return redirect(route('home'));
        }
        $meeting = Meeting::where('id',$meetingId)->first();
        $groups = Group::withCount('applications')->where('meeting_id',$meetingId)->get();
        $applications = Application::leftJoin('users','users.id','=','applications.user_id')
            ->select('users.username','applications.*')
            ->get();
        $founder = User::where('id',$meeting->founder_id)->first();
        return view('meetings.modifyGroups',[
            'meeting' => $meeting,
            'groups' =>$groups,
            'founder' =>$founder,
            'applications' => $applications,
        ]);
    }

    public function acceptUser(Request $request, $meetingId=null) {
        $user = User::where('username',$request->username)->first();
        DB::table('applications')->where('group_id',$request->group_id)
            ->where('user_id',$user->id)->update(['approval'=>true]);
        return back();
    }

    public function denyUser(Request $request,$meetingId = null) {
        $user = User::where('username',$request->username)->first();
        DB::table('applications')->where('group_id',$request->group_id)
            ->where('user_id',$user->id)->update(['approval'=>false]);
        return back();
    }
}
