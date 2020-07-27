<?php

namespace App\Repositories;
use App\Group;
class GroupRepositoryImpl implements GroupRepository {

    protected $model;

    function __construct()
    {
        $this->model = new Group;
    }


    public function store(Group $group)
    {
        $group->save();
    }

    public function findById(int $id)
    {
        try {
            $group = $this->model->where('id',$id)->firstOrFail();
        }catch (ModelNotFoundException $e) {
            Log::notice('Group::ID Not Found');
            return null;
        }
        return $group;
    }

    public function findByMeetingId(int $meetingId)
    {
        try {
            $group = $this->model->where('meeting_id',$meetingId)->get();
        }catch (ModelNotFoundException $e) {
            Log::notice('Group::meeting_id Not Found');
            return null;
        }
        return $group;
    }

    public function findByActive($date)
    {
        $groups = $this->model->where('act_end_date','>=',now())->get();
        return $groups;
    }


    public function findByMeetingIdApplicationCount(int $meetingId)
    {
        $groups = $this->model->withCount('applications')->where('meeting_id',$meetingId)->get();

        return $groups;
    }

    public function findByIdApplicationCount(int $id)
    {
        $groups = $this->model->withCount('applications')->where('id',$id)->first();

        return $groups;
    }
}