<?php

namespace App\Repositories;

use App\Meeting;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class MeetingRepositoryImpl implements MeetingRepository{

    protected $model;

    function __construct() {
        $this->model = new Meeting();
    }

    public function store(Meeting $meeting) {
        return $meeting->save();
    }

    public function findById(int $id) {
        try {
            $meeting = $this->model->where('id',$id)->firstOrFail();
        }catch (ModelNotFoundException $e) {
            Log::notice('Model::ID Not Found');
            return null;
        }
        return $meeting;
    }
    public function findByFounder(int $userId) {

        $meetings = $this->model->where('founder_id',$userId)->get();
        if ($meetings == null) {
            return null;
        }
        return $meetings;
    }

    public function plusViews(Meeting $meeting) {
        if ($meeting == null)
            return null;
        $meeting->views = $meeting->views + 1;
        $this->store($meeting);
        return $meeting->view;
    }

    public function sortedAll()
    {
        return $this->model->withCount('applications')->orderBy('applications_count','desc')
            ->orderBy('views','desc')->orderBy('created_at','desc')->get();
    }

}
