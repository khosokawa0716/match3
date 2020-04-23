<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = ['id', 'user_id'];

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }

    public function public_messages()
    {
        return $this->hasMany('App\PublicMessage')->orderBy('id', 'desc');
    }
}
