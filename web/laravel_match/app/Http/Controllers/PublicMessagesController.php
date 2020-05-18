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
        $user_id = Auth::id();

        $latest_public_message = PublicMessage::with(['project'])
            ->latest()
            ->where('user_id', $user_id)
            ->first();

        // 自分が投稿したパブリックメッセージのプロジェクトのidを取得する
        $exchaged_messages_project_ids = PublicMessage::where('user_id', $user_id)
            ->pluck('project_id');

        $exchanged_messages_projects = Project::with(['owner'])
            ->latest()
            ->whereIn('id', $exchaged_messages_project_ids)
            ->get();

        return [
            'latest_public_message' => $latest_public_message,
            'exchanged_messages_projects' => $exchanged_messages_projects
        ];
    }
}
