<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = ['id', 'user_id'];

    /** JSONに含める属性 */
    protected $visible = [
        'id', 'title', 'owner', 'type', 'minimum_amount', 'max_amount', 'detail', 'status'
    ];

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }

    public function applicant()
    {
        return $this->belongsTo('App\User', 'applicant_id', 'id', 'users');
    }

    public function public_messages()
    {
        return $this->hasMany('App\PublicMessage')->orderBy('id', 'desc');
    }

    public function private_messages()
    {
        return $this->hasMany('App\PrivateMessage')->orderBy('id', 'desc');
    }
}
