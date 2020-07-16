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
}
