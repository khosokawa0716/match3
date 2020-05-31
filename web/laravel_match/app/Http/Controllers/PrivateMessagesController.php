<?php

namespace App\Http\Controllers;

use App\PrivateMessage;
use App\Project;
use Illuminate\Support\Facades\Auth;

class PrivateMessagesController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    // メッセージ一覧画面に表示する情報をとってくる
    public function show()
    {
        $user_id = Auth::id();

        $unread_private_messages = PrivateMessage::with(['project'])
            ->latest()
            ->where('received_user_id', $user_id)
            ->where('unread', true)
            ->get();

        // やりとりしたプライベートメッセージのプロジェクトのidを取得する
        $exchanged_private_messages_project_ids = PrivateMessage::where('user_id', $user_id)
            ->orWhere('received_user_id', $user_id)
            ->pluck('project_id');

        // やりとりしたプロジェクトを取得する
        $exchanged_private_messages_projects = Project::with(['owner'])
            ->latest()
            ->whereIn('id', $exchanged_private_messages_project_ids)
            ->get();

        // 送受信したプライベートメッセージ
        $exchanged_private_messages = PrivateMessage::with(['author'])
            ->with(['project'])
            ->where('received_user_id', $user_id) // 受信したメッセージ
            ->orWhere('user_id', $user_id) // または送信したメッセージ
            ->orderBy(PrivateMessage::CREATED_AT, 'desc')->paginate();

        return [
            'unread_private_messages' => $unread_private_messages,
            'exchanged_private_messages_projects' => $exchanged_private_messages_projects,
            'exchanged_private_messages' => $exchanged_private_messages
        ];
    }
}
