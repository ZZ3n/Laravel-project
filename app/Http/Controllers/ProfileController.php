<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\User;
use App\Meeting;
use App\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function getProfile(Request $request) {
        if ($request->session()->has('is_login') == false) {
            return redirect('/home');
        }
        $user = User::where('id',$request->session()->get('uid'))->first();
        $meetings = Meeting::where('founder_id',$user->id)->get();
        $applications = Application::where('user_id',$user->id)
            ->leftJoin('groups','groups.id','=','applications.group_id')
            ->groupBy('groups.meeting_id')
            ->select('groups.meeting_id','applications.approval','groups.name');

        $user_apps = DB::table('meetings')->rightJoinSub($applications,'AG',function($join) {
           $join->on('AG.meeting_id','=','meetings.id');
        })->select('AG.name as group_name','AG.meeting_id','meetings.id','meetings.name','AG.approval')
        ->get();

//        ddd($user_apps);

        return view('Profile.profile',[
            'user' =>$user,
            'meetings' => $meetings,
            'user_apps' => $user_apps,
        ]);
    }

    public function gotoModifyProfile(Request $request) {
        $validator = $request->validate([
            'username' => ['bail','required','exists:users,username'],
            'password' => ['required'],
            'email' => ['required']
        ]);
        $user = User::where('username',$request->username)->first();
        if (! Hash::check($request->password,$user->password)) { // password check failed
            return redirect('/profile');
        }

        return view('Profile.modifyProfile',['user'=>$user]);
    }

    public function modifyProfile(Request $request) {
        $validator = $request->validate([
            'username' => ['bail','required','max:30'],
            'email' => ['bail','required','email'],
            'name' => ['required','max:30'],
            'password' => ['required','confirmed'],
            'password_confirmation' => ['required'],
        ]);

        $user = User::where('username',$request->session()->get('username'))->first();

        try {
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
        }
        catch(QueryException $exception) {
            return back();
        }

        $request->session()->forget(['is_login','uid','username']);

        return redirect('/home');
    }



}
