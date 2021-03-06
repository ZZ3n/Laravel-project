<?php

namespace App\Http\Controllers;

use App\Services\MeetingService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $meetingService;

    public function __construct(MeetingService $meetingService)
    {
        $this->meetingService = $meetingService;
    }

    public function home(Request $request) {
        $meetings = $this->meetingService->sortedAll();

        return view('home',['meetings' => $meetings]);
    }

}
