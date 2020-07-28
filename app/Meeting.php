<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Meeting extends Model
{
    protected $casts = [
      'name' => 'string',
        'content' => 'string',
        'views' => 'integer',
        'founder_id' => 'integer',
    ];

    public function user() {
        return $this->belongsTo('App\User','founder_id');
    }

    public function groups() {
        //미팅을 소유하고 있음.
        return $this->hasMany('App\Group');
    }

    public function applications()
    {
        return $this->hasManyThrough('App\Application', 'App\Group');
    }

    public static function fromRequest(Request $request) {
        $meeting = new Meeting;
        $meeting->name = $request->name;
        $meeting->founder_id = $request->user()->id;
        $meeting->content = $request->meeting_content;

        return $meeting;
    }

}
