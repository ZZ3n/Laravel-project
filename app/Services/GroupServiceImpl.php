<?php

namespace App\Services;

use App\Group;
use App\Repositories\GroupRepository;

class GroupServiceImpl implements GroupService {

    protected $groupRepository;
    /**
     * GroupServiceImpl constructor.
     */
    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function findByMeetingId(int $meetingId, bool $withCount = false)
    {
        if ($withCount == true) {
            $groups = $this->groupRepository->findByMeetingIdApplicationCount($meetingId);
        }else {
            $groups = $this->groupRepository->findByMeetingId($meetingId);
        }

        return $groups;
    }

    public function findById(int $id, bool $withCount = false)
    {
        if ($withCount == true) {
            $group = $this->groupRepository->findByIdApplicationCount($id);
        }else {
            $group = $this->groupRepository->findById($id);
        }

        return $group;
    }

    public function store(Group $group)
    {
        $this->groupRepository->store($group);
    }
}