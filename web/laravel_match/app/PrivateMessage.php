<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivateMessage extends Model
{
    /** JSONに含める属性 */
    protected $visible = [
        'author', 'content', 'project_id', 'created_at'
    ];

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }
}
