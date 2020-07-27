<?php

namespace App\Services;

use App\Application;

interface ApplicationService {

    public function findById($gid = null,$uid = null);
    public function approval(int $gid, int $uid);
    public function deny(int $gid, int $uid);
    public function create(int $gid, int $uid, string $reason = null, bool $approval = false);
    public function findUserApplications(int $uid);
    public function findMeetingApplications(int $meetingId);
}
