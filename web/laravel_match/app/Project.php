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

    /** JSONに含める属性 */
    protected $visible = [
        'id', 'title', 'owner', 'applicant', 'type', 'minimum_amount', 'max_amount', 'detail', 'status', 'created_at'
    ];

    /** 1ページあたりの項目数 */
    protected $perPage = 9;
}
