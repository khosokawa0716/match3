<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicMessage extends Model
{
//    protected $guarded = ['id', 'user_id', 'project_id'];

    /** JSONに含める属性 */
    protected $visible = [
        'author', 'project', 'content', 'created_at'
    ];

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_id', 'id', 'projects');
    }
}
