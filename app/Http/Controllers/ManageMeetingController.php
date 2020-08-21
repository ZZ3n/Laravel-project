<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMeeting;
use App\Services\ApplicationService;
use App\Services\GroupService;
use App\Services\MeetingService;
use App\Services\UserService;
use Illuminate\Http\Request;

class ManageMeetingController extends Controller
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

    public function fix(Request $request, $meetingId = null)
    {
        if (!$request->user()) {
            return redirect(route('home'));
        }
        $meeting = $this->meetingService->findById($meetingId);

        return view('meetings.modify', [
            'meeting' => $meeting,
        ]);
    }

    public function update(CreateMeeting $request, $meetingId = null)
    {

        $meeting = $this->meetingService->update($meetingId, $request);

        return redirect(route('meetings')  .'/'. $meeting->id);
    }

    public function acceptUser(Request $request, $meetingId = null)
    {

        $user = $this->userService->findByUsername($request->username);
        $this->applicationService->approval($request->group_id, $user->id);

        return back();
    }

    public function denyUser(Request $request, $meetingId = null)
    {

        $user = $this->userService->findByUsername($request->username);
        $this->applicationService->deny($request->group_id, $user->id);

        return back();
    }
}
