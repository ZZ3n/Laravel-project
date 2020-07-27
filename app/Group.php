<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $casts = [
        'meeting_id' => 'integer',
        'capacity' => 'integer',
        'name' => 'string',
    ];

    public function meeting() {
        return $this->belongsTo('App\User','meetings_id');
    }

    public function applications() {
        //application을 소유하고 있음.
        return $this->hasMany('App\Application');
    }

    public static function fromSessionJson(int $meetingId, $json) {
        $info = json_decode($json);
        $group = new Group();
        $group->name = $info->group_name;
        $group->capacity = $info->capacity;
        $group->apply_start_date = $info->apply_start_date;
        $group->apply_end_date = $info->apply_end_date;
        $group->act_start_date = $info->action_start_date;
        $group->act_end_date = $info->action_end_date;
        $group->approval_opt = $info->apv_opt;
        $group->meeting_id = $meetingId;

        return $group;
    }
}
