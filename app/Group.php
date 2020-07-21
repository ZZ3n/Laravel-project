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

}
