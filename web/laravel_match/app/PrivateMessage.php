<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivateMessage extends Model
{
    /** JSONに含める属性 */
    protected $visible = [
        'author', 'project', 'content', 'project_id', 'created_at'
    ];

    public function author() // メッセージを投稿する人
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_id', 'id', 'projects');
    }
}
