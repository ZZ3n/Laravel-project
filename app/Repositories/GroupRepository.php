<?php

namespace App\Repositories;

use App\Group;

interface GroupRepository {

    public function store(Group $group);
    public function findById(int $id);
    public function findByMeetingId(int $meetingId);
    public function findByMeetingIdApplicationCount(int $meetingId);
    public function findByIdApplicationCount(int $id);
}