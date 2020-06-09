<?php

namespace App\Http\Controllers;

use App\Application;
use App\Project;
use App\PrivateMessage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivateMessagesDetailController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    // 案件と非公開メッセージをとってくる
    public function show($data)
    {
        $application_id = $data; // 引数は$dataは、applicationsのid

        if (ctype_digit($application_id)) {
            // 0.非公開メッセージを閲覧できるユーザーなのか判定する
            // owner_idの取得
            $owner_id =Application::where('id', $application_id)
                ->value('owner_id');

            // applicant_idの取得
            $applicant_id =Application::where('id', $application_id)
                ->value('applicant_id');

            // ログインユーザーのidが、owner_idまたはapplicant_idに一致しないといけない
            // いずれかのidに一致していない場合は403（Forbidden.vue）を返却する
            if ($owner_id !== Auth::id() && $applicant_id !== Auth::id()){
                return abort(403);
            }

            // 1.対象の案件を取得する
            $project_id = Application::where('id', $application_id)
                ->value('project_id');

            $project = Project::where('id', $project_id)
                ->with(['owner'])
                ->first();

                // 検索結果がない場合には、エラーコード404を返却する
            if ($project === null) {
                return abort(404);
            }

            // 2.applicationに紐づいている非公開メッセージを取得する
            $private_messages = PrivateMessage::where('application_id', $application_id)
                ->with(['author'])
                ->oldest()
                ->get();

            // 3.メッセージを受け取った側が確認したものを既読にする
            PrivateMessage::where('application_id', $application_id)
                ->where('received_user_id', Auth::id())
                ->update(['unread' => false]);

            // 4.応募者の情報を取得する
            $applicant = User::where('id',$applicant_id)
                ->first();

            // 5.案件と紐づく非公開メッセージ、応募者を返却
            return [
                'project' => $project,
                'private_messages' => $private_messages,
                'applicant' => $applicant
                    ];
        } else {
            // URLのID部分に数値でない入力でリクエストがあった場合にも、エラーコード404を返却する
            return abort(404);
        }
    }

    // 非公開メッセージを投稿する
    public function create(Request $request, PrivateMessage $privateMessage, $data)
    {
        $user_id = Auth::id(); // メッセージを投稿する人
        $application_id = $data; // 引数は$dataは、applicationsのid

        // project_idの取得
        $project_id = Application::where('id', $application_id)
            ->value('project_id');
        // owner_idの取得
        $owner_id =Application::where('id', $application_id)
            ->value('owner_id');
        // applicant_idの取得
        $applicant_id =Application::where('id', $application_id)
            ->value('applicant_id');

        // メッセージの送り先 received_user_id を特定する
        // **************************************************************
        if ($owner_id === $user_id) { // ログインユーザーが案件登録者なら、
            $received_user_id = $applicant_id; // 送り先は案件に応募した人
        } else if ($applicant_id === $user_id) { // ログインユーザーが応募した人なら、
            $received_user_id = $owner_id; // 送り先は案件登録者
        } else {
            // showメソッドで権限チェックをおこなっており、通常ありえないが、
            // どちらにもあてはまらない場合は403を返却する
            return abort(403);
        }
        // **************************************************************

        $request->validate([
            'content' => 'required|string|max:200'
        ]);

        $privateMessage->application_id = $application_id;
        $privateMessage->project_id = $project_id;
        $privateMessage->user_id = $user_id;
        $privateMessage->received_user_id = $received_user_id;
        $privateMessage->content = $request['content'];
        $privateMessage->save();

        $private_messages = PrivateMessage::where('application_id', $application_id)
            ->with(['author'])
            ->oldest()
            ->get();

        return $private_messages;
    }
}
