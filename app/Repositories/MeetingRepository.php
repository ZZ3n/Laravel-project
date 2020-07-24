<?php

namespace App\Repositories;

use App\Meeting;

interface MeetingRepository {

    public function store(Meeting $meeting);

    public function findById(int $id);
    public function findByFounder(int $userId);
    public function plusViews(Meeting $meeting);

}