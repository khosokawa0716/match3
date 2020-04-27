<?php

namespace App\Http\Controllers;

use App\PrivateMessage;
use App\Project;
use App\PublicMessage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectDetailController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    // 案件とパブリックメッセージを取得する
    // 引数はprojectsのid
    public function show($data)
    {
        $id = $data;

        if (ctype_digit($id)) {
            $project = Project::where('id', $id)->with(['owner'])->first();
            // 検索結果がない場合には、エラーコード404を返却する
            if ($project === null) { return abort(404); }

            $public_messages = PublicMessage::where('project_id', $id)->with(['author'])->orderBy(PublicMessage::CREATED_AT, 'desc')->get();
            return [ 'project' => $project, 'public_messages' => $public_messages ];
        } else {
            // URLのID部分に数値でない入力でリクエストがあった場合にも、エラーコード404を返却する
            return abort(404);
        }
    }

    // パブリックメッセージを投稿する
    public function create(Request $request, PublicMessage $publicMessage, $data)
    {
        $user_id = Auth::id();
        $project_id = $data;

        $request->validate([
            'content' => 'required|string|max:2550'
        ]);

        $publicMessage->user_id = $user_id;
        $publicMessage->project_id = $project_id;
        $publicMessage->content = $request['content'];
        $publicMessage->save();

        $public_messages = PublicMessage::where('project_id', $project_id)->with(['author'])->orderBy(PublicMessage::CREATED_AT, 'desc')->get();

        return $public_messages;
    }

    // 案件に応募する。同時に応募した旨を案件登録者にプライベートメッセージで伝える
    public function update(PrivateMessage $privateMessage, $data)
    {
//        Log::info('ProjectDetailControllerのupdate起動');
        $id = $data;
        Log::info('projectのid'.$id);
        $user = Auth::user();
        $user_id = Auth::id();
        $name = $user->name;

        if (ctype_digit($id)) {
            $project = Project::where('id', $id)->with(['owner'])->first();
            // 検索結果がない場合には、エラーコード404を返却する
            if ($project === null) { return abort(404); }

            // vueファイルでボタンを非表示にしているので普通はあり得ないが、以下2つのケースでエラーコード403を返却する
            // 1.応募が終了した案件に応募しようとした
            // 2.自分が登録した案件に応募しようとした
            if ($project->status === 0 || $project->user_id === $user_id) { return abort(403); }

            $project->status = 0;
            $project->applicant_id = $user_id;
            $project->save();

            $user->number_unread_messages += 1;
            $user->save();

            $privateMessage->user_id = $user_id;
            $privateMessage->project_id = $id;
            $privateMessage->content = $name.'さんがこの案件に応募しました!';
            $privateMessage->save();

            return $project;
        } else {
            // URLのID部分に数値でない入力でリクエストがあった場合は、エラーコード404を返却する
            return abort(404);
        }
    }
}
