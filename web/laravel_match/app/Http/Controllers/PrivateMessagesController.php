<?php

namespace App\Http\Controllers;

use App\PrivateMessage;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PrivateMessagesController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    public function show()
    {
//        Log::info('PrivateMessagesController.show起動');
        $user_id = Auth::id();

        $unread_private_messages = PrivateMessage::with(['project'])
            ->latest()
            ->where('received_user_id', $user_id)
            ->where('unread', true)
            ->get();
//        Log::debug(print_r($unread_private_messages, true));

        // やりとりしたプライベートメッセージのプロジェクトのidを取得する
        $exchaged_private_messages_project_ids = PrivateMessage::where('user_id', $user_id)
            ->orWhere('received_user_id', $user_id)
            ->pluck('project_id');
//        Log::debug(print_r($exchaged_private_messages_project_ids, true));

        // やりとりしたプロジェクトを取得する
        $exchaged_private_messages_projects = Project::with(['owner'])
            ->latest()
            ->whereIn('id', $exchaged_private_messages_project_ids)
            ->get();
//        Log::debug(print_r($exchaged_private_messages_projects, true));

        return [
            'unread_private_messages' => $unread_private_messages,
            'exchaged_private_messages_projects' => $exchaged_private_messages_projects
        ];
    }
}
