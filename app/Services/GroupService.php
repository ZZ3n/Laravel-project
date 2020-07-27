<?php

namespace App\Services;

use App\Group;

interface GroupService {

    public function findByMeetingId(int $meetingId, bool $withCount = false);
    public function findById(int $id,  bool $withCount = false);
    public function store(Group $group);
}