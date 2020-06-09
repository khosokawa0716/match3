<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    /** JSONに含める属性 */
    protected $visible = [
        'id', 'applicant', 'project_id', 'owner_id', 'applicant_id', 'created_at'
    ];

    public function applicant()
    {
        return $this->belongsTo('App\User', 'applicant_id', 'id', 'users');
    }
}
