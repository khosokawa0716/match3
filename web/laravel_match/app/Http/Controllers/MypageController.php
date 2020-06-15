<?php

namespace App\Http\Controllers;

use App\Application;
use App\PrivateMessage;
use App\Project;
use App\PublicMessage;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    /**
     * マイページに表示する情報をとってくる
     * @return Array
     */
    public function index(){
        $id = Auth::id();

        // 登録した案件
        $registered_projects = Project::with(['owner'])
            ->where('user_id', $id)
            ->orderBy(Project::CREATED_AT, 'desc')
            ->get();

        // 応募した案件
        // 応募した案件を探すために、自分が応募した案件のidを探す
        $applied_project_ids = Application::where('applicant_id', $id)
            ->pluck('project_id');
        $applied_projects = Project::with(['owner'])
            ->whereIn('id', $applied_project_ids)
            ->orderBy(Project::CREATED_AT, 'desc')
            ->get();

        // 受信したメッセージを探すために、自分が登録した案件のidを取得する
        // メッセージのidには必ず1つの案件のidが紐づいているため
        $registered_project_ids = \DB::table('projects')
            ->where('user_id', $id)
            ->pluck('id');

        // 送受信したパブリックメッセージ
        $exchanged_public_messages = PublicMessage::with(['author'])
            ->with(['project'])
            ->whereIn('project_id', $registered_project_ids) // 受信したメッセージ
            ->orWhere('user_id', $id) // または送信したメッセージ
            ->orderBy(PublicMessage::CREATED_AT, 'desc')
            ->get();

        // 送受信したプライベートメッセージ
        $exchanged_private_messages = PrivateMessage::with(['author'])
            ->with(['application'])
            ->with(['project'])
            ->where('received_user_id', $id) // 受信したメッセージ
            ->orWhere('user_id', $id) // または送信したメッセージ
            ->orderBy(PrivateMessage::CREATED_AT, 'desc')
            ->get();

        // 未読の受信した非公開メッセージの件数
        $number_unread_private_messages = PrivateMessage::with(['author'])
            ->where('received_user_id', $id)
            ->where('unread',true)
            ->count();

        return [
            'registered_projects' => $registered_projects,
            'applied_projects' => $applied_projects,
            'exchanged_public_messages' => $exchanged_public_messages,
            'exchanged_private_messages' => $exchanged_private_messages,
            'number_unread_private_messages' => $number_unread_private_messages
            ];
    }
}
