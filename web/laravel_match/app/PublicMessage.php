<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicMessage extends Model
{
    protected $guarded = ['id', 'user_id', 'project_id'];

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }
}
