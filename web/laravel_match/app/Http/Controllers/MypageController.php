<?php

namespace App\Http\Controllers;

use App\PrivateMessage;
use App\Project;
use App\PublicMessage;
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

        // 登録した案件
        $registered_projects = Project::with(['owner'])
            ->where('user_id', $id)
            ->orderBy(Project::CREATED_AT, 'desc')
            ->get();
        Log::debug('$registered_projectsの中身: '.print_r($registered_projects, true));

        // 応募した案件
        $applied_projects = Project::with(['owner'])
            ->where('applicant_id', $id)
            ->orderBy(Project::CREATED_AT, 'desc')
            ->get();
        Log::debug('$applied_projectsの中身: '.print_r($applied_projects, true));

        // 受信したメッセージを探すために、自分が登録した案件のidを取得する
        // メッセージのidには必ず1つの案件のidが紐づいているため
        $registered_project_ids = \DB::table('projects')
            ->where('user_id', $id)
            ->pluck('id');
//        Log::debug('$registered_project_idsの中身: '.print_r($registered_project_ids, true));

        // 送受信したパブリックメッセージ
        $exchanged_public_messages = PublicMessage::with(['author'])
            ->with(['project'])
            ->whereIn('project_id', $registered_project_ids) // 受信したメッセージ
            ->orWhere('user_id', $id) // または送信したメッセージ
            ->orderBy(PublicMessage::CREATED_AT, 'desc')->paginate();

        // 送受信したプライベートメッセージ
        $exchanged_private_messages = PrivateMessage::with(['author'])
            ->with(['project'])
            ->where('received_user_id', $id) // 受信したメッセージ
            ->orWhere('user_id', $id) // または送信したメッセージ
            ->orderBy(PrivateMessage::CREATED_AT, 'desc')->paginate();

        // 未読の受信した非公開メッセージの件数
        $number_unread_private_messages = PrivateMessage::with(['author'])
            ->where('received_user_id', $id)
            ->where('unread',true)
            ->count();
//        Log::debug('$unread_private_messagesの中身: '.print_r($unread_private_messages, true));

        return [
            'registered_projects' => $registered_projects,
            'applied_projects' => $applied_projects,
            'exchanged_public_messages' => $exchanged_public_messages,
            'exchanged_private_messages' => $exchanged_private_messages,
            'number_unread_private_messages' => $number_unread_private_messages
            ];
    }
}
