<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicMessage extends Model
{
//    protected $guarded = ['id', 'user_id', 'project_id'];

    /** JSONに含める属性 */
    protected $visible = [
        'author', 'content',
    ];

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }
}
