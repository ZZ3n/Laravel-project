<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home(Request $request) {
        if ($request->session()->has('is_login') && $request->session()->get('is_login')) {
            $user = User::where('id',$request->session()->get('uid'))->get()->first();
        }
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
            ->selectRaw('meetings.id,meetings.name,meetings.views,meetings.created_at,G.act_end_date,applies')
            ->leftJoinSub($groups,'G',function($join) {
                $join->on('meetings.id','=','G.meeting_id');
            })
            ->orderBy('views','desc')
            ->orderBy('created_at','asc')->get();

        return view('home',['meetings' => $meetings]);
    }

}
