<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        $this->hasManyThrough('App\Application', 'App\Group', 'founder_id', 'group_id','id','id');
    }
}
