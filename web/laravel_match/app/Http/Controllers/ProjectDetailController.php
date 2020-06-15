<?php

namespace App\Http\Controllers;

use App\Application;
use App\PrivateMessage;
use App\Project;
use App\PublicMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectDetailController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    /**
     * 案件詳細ページ。案件と投稿されたパブリックメッセージを取得する
     * @param int $data
     * @return Array
     */
    public function show($data)
    {
        $id = $data; // $dataは、projectsのid

        if (ctype_digit($id)) {
            $project = Project::where('id', $id)->with(['owner'])->first();
            // 検索結果がない場合には、エラーコード404を返却する
            if ($project === null) { return abort(404); }

            $public_messages = PublicMessage::where('project_id', $id)
                ->with(['author'])
                ->oldest()
                ->get();

            // ログインユーザーが、すでに応募した案件かどうか
            // フロント側でボタンの表示・非表示の切り替えに使う
            $is_user_applied = Application::where('project_id', $id)
                ->where('applicant_id', Auth::id())
                ->exists();

            return [
                'project' => $project,
                'public_messages' => $public_messages,
                'is_user_applied' => $is_user_applied
            ];
        } else {
            // URLのID部分に数値でない入力でリクエストがあった場合にも、エラーコード404を返却する
            return abort(404);
        }
    }

    /**
     * パブリックメッセージを投稿する
     * @param \Illuminate\Http\Request $request
     * @param \App\PublicMessage $publicMessage
     * @param int $data
     * @return Array
     */
    public function create(Request $request, PublicMessage $publicMessage, $data)
    {
        $user_id = Auth::id();
        $project_id = $data; // $dataは、projectsのid

        $request->validate([
            'content' => 'required|string|max:200'
        ]);

        $publicMessage->user_id = $user_id;
        $publicMessage->project_id = $project_id;
        $publicMessage->content = $request['content'];
        $publicMessage->save();

        $public_messages = PublicMessage::where('project_id', $project_id)->with(['author'])->orderBy(PublicMessage::CREATED_AT, 'desc')->get();

        return $public_messages;
    }

    /**
     * 案件に応募する。同時に応募した旨を案件登録者にプライベートメッセージで伝える
     * @param \App\PrivateMessage $privateMessage
     * @param \App\Application $application
     * @param int $data
     * @return Array
     */
    public function update(PrivateMessage $privateMessage, Application $application, $data)
    {
        $project_id = $data; // $dataは、projectsのid

        // 応募した人 = ログインして応募の操作をした人
        $applicant_id = Auth::id();

        if (ctype_digit($project_id)) {
            $project = Project::where('id', $project_id)->with(['owner'])->first();
            // 検索結果がない場合には、エラーコード404を返却する
            if ($project === null) { return abort(404); }

            // 一度応募した案件に2度以上応募しないチェック nullが返ってくれば応募できる
            $application_check = Application::where('project_id', $project_id)
                ->where('applicant_id', $applicant_id)
                ->first();

            // vueファイルでボタンを非表示にしているので普通はあり得ないが、以下2つのケースでエラーコード403を返却する
            // 1.応募が終了した案件に応募しようとした
            // 2.自分が登録した案件に応募しようとした
            // 3.同じ案件に2度以上応募しようとした
            if ($project->status === 0 || $project->user_id === $applicant_id || $application_check !== null) { return abort(403); }

            // applicationsテーブルのレコードを登録
            $application->project_id = $project_id;
            $application->owner_id = $project->user_id;
            $application->applicant_id = $applicant_id;
            $application->save();

            // 応募した旨を案件登録者にプライベートメッセージで伝える
            // private_messagesテーブルのレコードを登録
            $privateMessage->application_id = $application->id;
            $privateMessage->project_id = $project_id;
            $privateMessage->user_id = $applicant_id;
            $privateMessage->received_user_id = $project->user_id; // 相手はプロジェクトのオーナー 2020/04/29に追加
            $privateMessage->content = $project->title.'の案件に応募がありました!';
            $privateMessage->save();

            return $project;
        } else {
            // URLのID部分に数値でない入力でリクエストがあった場合は、エラーコード404を返却する
            return abort(404);
        }
    }
}
