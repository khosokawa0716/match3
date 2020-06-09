<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    /** JSONに含める属性 */
    protected $visible = [
        'id', 'applicant', 'project_id', 'owner_id', 'applicant_id', 'created_at'
    ];

    // DBが整数型であるが、本番環境ではstring型でとってきてしまうため追記
    protected $casts = [
        'id' => 'int',
        'project_id' => 'int',
        'owner_id' => 'int',
        'applicant_id' => 'int',
        'delete_flg' => 'int'
    ];

    public function applicant()
    {
        return $this->belongsTo('App\User', 'applicant_id', 'id', 'users');
    }
}
