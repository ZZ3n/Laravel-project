<?php

namespace App\Services;

use App\Application;
use App\Repositories\ApplicationRepository;
use App\Repositories\GroupRepository;
use App\Repositories\MeetingRepository;
use App\Repositories\UserRepository;

class ApplicationServiceImpl implements ApplicationService
{

    protected $userRepository;
    protected $applicationRepository;
    protected $groupRepository;
    protected $meetingRepository;

    public function __construct(
        ApplicationRepository $applicationRepository, UserRepository $userRepository,
        GroupRepository $groupRepository, MeetingRepository $meetingRepository)
    {
        $this->applicationRepository = $applicationRepository;
        $this->groupRepository = $groupRepository;
        $this->meetingRepository = $meetingRepository;
        $this->userRepository = $userRepository;
    }

    public function findById($gid = null, $uid = null)
    {
        if ($gid != null && $uid != null) {
            $application = $this->applicationRepository->findById($gid, $uid);
        } else if ($uid != null) {
            $application = $this->applicationRepository->findByUserId($uid);
        } else if ($gid != null) {
            $application = $this->applicationRepository->findByGroupId($gid);
        } else {
            $application = null;
        }

        return $application;
    }

    public function approval(int $gid, int $uid)
    {
        $application = $this->applicationRepository->findById($gid, $uid);
        $this->applicationRepository->approval($application);
    }

    public function deny(int $gid, int $uid)
    {
        $application = $this->applicationRepository->findById($gid, $uid);
        $this->applicationRepository->deny($application);
    }

    public function create(int $gid, int $uid, string $reason = null, bool $approval = false)
    {
        $application = new Application;
        $application->user_id = $uid;
        $application->group_id = $gid;
        $application->reason = $reason;
        $application->approval = $approval;
        $this->applicationRepository->store($application);
        return $application;
    }

    public function findUserApplications(int $uid)
    {
        $applications = $this->applicationRepository->findByUserId($uid);
        $applicationsWithNames = $applications->map(function ($application) {
            $group = $this->groupRepository->findById($application->group_id);
            $meeting = $this->meetingRepository->findById($group->meeting_id);
            return [
                'meeting_name' => $meeting->name,
                'meeting_id' => $meeting->id,
                'group_name' => $group->name,
                'approval' => $application->approval,
            ];
        });

        return $applicationsWithNames;
    }

    public function findMeetingApplications(int $meetingId)
    {
        $meeting = $this->meetingRepository->findById($meetingId);
//        $groups = $this->groupRepository->findByMeetingId($meeting->id);
        //$applications = collect();
//        foreach ($groups as $group) {
//            $applications = $applications->merge($this->applicationRepository->findByGroupId($group->id));
//        }
        $applications = $meeting->applications;
        $applications_mapped = $applications->map(function ($application, $key) {
            $username = $this->userRepository->findById($application->user_id)->username;
            return [
                'username' => $username,
                'group_id' => $application->group_id,
                'reason' => $application->reason,
                'approval' => $application->approval,
            ];
        });

        return $applications_mapped;
    }

}