<?php

namespace App\Repositories;

use App\Application;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApplicationRepositoryImpl implements ApplicationRepository {

    protected $model;

    /**
     * ApplicationRepositoryImpl constructor.
     * @param $model
     */
    public function __construct()
    {
        $this->model = new Application;
    }

    public function store(Application $application)
    {
        $application->save();
    }

    public function findById(int $gid, int $uid)
    {
        try {
            $application = $this->model->where('group_id',$gid)->where('user_id',$uid)->firstOrFail();
        }catch (ModelNotFoundException $e) {
            return null;
        }
        return $application;
    }

    public function findByGroupId(int $groupId)
    {
        $applications = $this->model->where('group_id',$groupId)->get();

        return $applications;
    }

    public function findByUserId(int $userId)
    {
        $applications = $this->model->where('user_id',$userId)->get();

        return $applications;
    }

    public function approval(Application $application)
    {
        $application->approval = true;
        $this->store($application);
    }

    public function deny(Application $application)
    {
        $application->approval = false;
        $this->store($application);
    }
}
