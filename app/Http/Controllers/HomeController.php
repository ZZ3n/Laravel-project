<?php

namespace App\Http\Controllers;

use App\Services\MeetingService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $meetingService;

    public function __construct(MeetingService $meetingService)
    {
        $this->meetingService = $meetingService;
    }

    public function home(Request $request) {
        if ($request->session()->has('is_login') && $request->session()->get('is_login')) {
            $user = User::where('id',$request->session()->get('uid'))->get()->first();
        }

        $meetings = $this->meetingService->sortedAll();

        return view('home',['meetings' => $meetings]);
    }

}
