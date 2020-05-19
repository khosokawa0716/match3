<?php

namespace App\Http\Controllers;

use App\Project;
use App\PrivateMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PrivateMessagesDetailController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    // 案件と非公開メッセージを取得する
    // 引数はprojectsのid
    public function show($data)
    {
//        Log::info('PrivateMessagesDetailController.showの起動');
        $project_id = $data;
//        Log::info('$project_idの中身: '.$project_id);

        if (ctype_digit($project_id)) {
            // 1.対象の案件を取得する
            $project = Project::where('id', $project_id)
                ->with(['owner'])
                ->with(['applicant'])
                ->first();

                // 検索結果がない場合には、エラーコード404を返却する
            if ($project === null) {
//                Log::info('projectの検索結果がnullだった');
                return abort(404);
            }

            // 2.案件に紐づいている非公開メッセージを取得する
            $private_messages = PrivateMessage::where('project_id', $project_id)
                ->with(['author'])
//                ->update(['unread' => false])
                ->orderBy(PrivateMessage::CREATED_AT, 'asc')
                ->get();

            // 3.メッセージを受け取った側が確認したものを既読にする
            PrivateMessage::where('project_id', $project_id)
                ->where('received_user_id', Auth::id())
                ->update(['unread' => false]);

//            Log::info('$private_messagesの中身: '.print_r($private_messages, true));
            // 4.案件と紐づく非公開メッセージを返却
            return [ 'project' => $project, 'private_messages' => $private_messages ];
        } else {
            // URLのID部分に数値でない入力でリクエストがあった場合にも、エラーコード404を返却する
            return abort(404);
        }
    }

    // 非公開メッセージを投稿する
    public function create(Request $request, PrivateMessage $privateMessage, $data)
    {
        Log::info('PrivateMessagesDetailController.createの起動');
        $user_id = Auth::id(); // メッセージを投稿する人
        $project_id = $data;

        // メッセージの送り先 received_user_id を特定する
        // **************************************************************
            // 1.プロジェクトを取得
        if (ctype_digit($project_id)) {
            $project = Project::where('id', $project_id)->with(['owner'])->first();
            // 検索結果がない場合には、エラーコード404を返却する
            if ($project === null) { return abort(404); }

        } else {
            // URLのID部分に数値でない入力でリクエストがあった場合にも、エラーコード404を返却する
            return abort(404);
        }
            // 2.案件を登録した人=projectsテーブルのuser_idが、メッセージの投稿者と
            // 一致すれば、received_user_idは応募した人=projectsテーブルのapplicant_id
            // 違っていれば、received_user_idは登録した人=projectsテーブルのuser_id
        if ($project->user_id === $user_id) {
            $received_user_id = $project->applicant_id;
        } else {
            $received_user_id = $project->user_id;
        }
        // **************************************************************
        Log::info('$received_user_idの中身: '.print_r($received_user_id, true));

        $request->validate([
            'content' => 'required|string|max:200'
        ]);

        $privateMessage->user_id = $user_id;
        $privateMessage->received_user_id = $received_user_id;
        $privateMessage->project_id = $project_id;
        $privateMessage->content = $request['content'];
        $privateMessage->save();

        $private_messages = PrivateMessage::where('project_id', $project_id)->with(['author'])->orderBy(PrivateMessage::CREATED_AT, 'asc')->get();

        return $private_messages;
    }
}
