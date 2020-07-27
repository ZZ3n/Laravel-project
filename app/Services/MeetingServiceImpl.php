<?php

namespace App\Services;

use App\Repositories\MeetingRepository;
use App\Meeting;
use Illuminate\Http\Request;

class MeetingServiceImpl implements  MeetingService {

    protected $meetingRepository;

    function __construct(MeetingRepository $meetingRepository) {
        $this->meetingRepository = $meetingRepository;
    }

    public function getDetail(int $id)
    {
        $meeting = $this->meetingRepository->findById($id);
        $this->meetingRepository->plusViews($meeting);
        return $meeting;
    }


    public function findById(int $id) {
        $meeting = $this->meetingRepository->findById($id);
        return $meeting;
    }

    public function update(int $id,Request $request) {
        $meeting = $this->findById($id);
        if ($meeting == null) {
            return false;
        }
        $meeting->name = $request->name;
        $meeting->content = $request->meeting_content;
        $this->meetingRepository->store($meeting);

        return $meeting;
    }

    public function findByFounder(int $founder_id) {
        $meeting = $this->meetingRepository->findByFounder($founder_id);

        return $meeting;
    }

    public function store(Meeting $meeting)
    {
        $check = $this->meetingRepository->store($meeting);
        return $check;
    }
}