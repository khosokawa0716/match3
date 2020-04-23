<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MypageController extends Controller
{
    //
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    public function index(){
//        Log::info('MypageControllerのindex起動');
        $id = Auth::id();
        $projects = Project::with(['owner'])
            ->where('user_id', $id)
            ->orderBy(Project::CREATED_AT, 'desc')->paginate();

        return $projects;
    }
}
