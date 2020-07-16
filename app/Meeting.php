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
}
