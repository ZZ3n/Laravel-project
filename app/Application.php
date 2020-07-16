<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $casts = [
      'approval' => 'boolean',
        'user_id' => 'integer',
        'group_id' => 'integer',
    ];
}
