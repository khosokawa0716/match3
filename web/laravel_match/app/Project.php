<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = ['id', 'user_id'];

    // DBが整数型であるが、本番環境ではstring型でとってきてしまうため追記
    protected $casts = [
        'user_id' => 'int',
        'applicant_id' => 'int',
        'minimum_amount' => 'int',
        'max_amount' => 'int',
        'status' => 'int',
        'delete_flg' => 'int'
    ];

    public function owner() // 案件を登録した人
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }

    public function applicant() // 案件に応募した人
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

    /** ページングで1ページに表示する数 */
    protected $perPage = 9;
}
