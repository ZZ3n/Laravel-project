<?php

namespace App\Repositories;

use App\Application;

interface ApplicationRepository {

    public function store(Application $application);

    public function findById(int $gid, int $uid);
    public function findByGroupId(int $groupId);
    public function findByUserId(int $userId);

    public function approval(Application $application);
    public function deny(Application $application);

}