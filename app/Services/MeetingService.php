<?php

namespace App\Services;

use Illuminate\Http\Request;

interface MeetingService {

    public function getDetail(int $id);
    public function findById(int $id);
    public function update(int $id,Request $request);
    public function findByFounder(int $founder_id);
}