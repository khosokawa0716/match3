<?php

namespace App\Http\Controllers;

use App\Project;
use App\PublicMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PublicMessagesController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    public function show()
    {
//        Log::info('PublicMessagesController.show起動');
        $user_id = Auth::id();

        $latest_public_message = PublicMessage::latest()
            ->where('user_id', $user_id)
            ->first();
//        Log::debug(print_r($latest_public_message,true));

        // 自分が投稿したパブリックメッセージのプロジェクトのidを取得する
        $exchaged_messages_project_ids = PublicMessage::where('user_id', $user_id)
            ->pluck('project_id');
//        Log::debug(print_r($exchaged_messages_project_ids,true));

        $exchanged_messages_projects = Project::latest()
            ->whereIn('id', $exchaged_messages_project_ids)
            ->get();
//        Log::debug(print_r($exchanged_messages_projects,true));

        return [
            'latest_public_message' => $latest_public_message,
            'exchanged_messages_projects' => $exchanged_messages_projects
        ];
    }
}
