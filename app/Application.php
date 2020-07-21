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

    public function user() {
        return $this->belongsTo('App\User','user_id');
    }

    public function group() {
        return $this->belongsTo('App\Group','group_id');
    }

}
