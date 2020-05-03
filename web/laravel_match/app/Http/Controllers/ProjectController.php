<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth')->except(['index']);
    }

    public function index(){
//        Log::info('ProjectControllerのindex起動');
        $projects = Project::with(['owner'])
            ->orderBy(Project::CREATED_AT, 'desc')->paginate();

        return $projects;
    }

    public function create(Request $request, Project $project) {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required',
            'minimum_amount' => 'nullable|integer|max:10000000',
            'max_amount' => 'nullable|integer|max:10000000',
            'detail' => 'required|string|max:2550'
        ]);

        $project->title = $request['title'];
        $project->user_id = $request->user()->id;
        $project->type = $request['type'];
        $project->minimum_amount = $request['minimum_amount'];
        $project->max_amount = $request['max_amount'];
        $project->detail = $request['detail'];

        $project->save();

        return $project;
    }

    // 編集する案件を取得する。引数$dataはprojectsのid
    public function edit($data)
    {
        $user_id = Auth::id();
        $project_id = $data;
//        Log::info('ProjectControllerのedit起動');
        if (ctype_digit($project_id)) {
            $project = Project::find($project_id);
            // 検索結果がない場合には、エラーコード404を返却する
            if ($project === null) { return abort(404); }

            // vueファイルでボタンを非表示にしているので普通はあり得ないが、以下2つのケースでエラーコード403を返却する
            // 1.応募が終了した案件を編集しようとした
            // 2.他人が登録した案件を編集しようとした
            if ($project->status === 0 || $project->user_id !== $user_id) { return abort(403); }

            return $project;
        } else {
            // URLのID部分に数値でない入力でリクエストがあった場合にも、エラーコード404を返却する
            return abort(404);
        }
    }

    // 案件の更新。引数は、Request $request:編集フォームに入力された値と $id:projectsのid
    public function update (Request $request, $id)
    {
        $user_id = Auth::id();
        $project_id = $id;

//        Log::info('ProjectControllerのupdate起動');
        if (ctype_digit($project_id)) {
            $project = Project::find($project_id);
            // 検索結果がない場合には、エラーコード404を返却する
            if ($project === null) { return abort(404); }

            // vueファイルでボタンを非表示にしているので普通はあり得ないが、以下2つのケースでエラーコード403を返却する
            // 1.応募が終了した案件を編集しようとした
            // 2.他人が登録した案件を編集しようとした
            if ($project->status === 0 || $project->user_id !== $user_id) { return abort(403); }

        } else {
            // URLのID部分に数値でない入力でリクエストがあった場合にも、エラーコード404を返却する
            return abort(404);
        }

        // 入力された項目だけ更新をおこなう
        // 入力項目の有無に関わらずプロジェクト情報を返却する
        if ( $request->filled('title') | $request->filled('type') | $request->filled('minimum_amount') | $request->filled('max_amount') | $request->filled('detail') ) {

            if ( $request->filled('title')) {
                $this->validate($request, [
                    'title' => 'string|max:255'
                ]);
                $project->title = $request['title'];
            }

            if ( $request->filled('type')) {
                // ラジオボタンなので、バリデーションなし
                $project->type = $request['type'];
            }

            if ( $request->filled('minimum_amount')) {
                $this->validate($request, [
                    'minimum_amount' => 'integer|max:10000000'
                ]);
                $project->minimum_amount = $request['minimum_amount'];
            }

            if ( $request->filled('max_amount')) {
                $this->validate($request, [
                    'max_amount' => 'integer|max:10000000'
                ]);
                $project->max_amount = $request['max_amount'];
            }

            if ( $request->filled('detail') ) {
                $this->validate($request, [
                    'detail' => 'string|max:2550'
                ]);
                $project->detail = $request['detail'];
            }

            $project->save();
        }

        return $project;
    }
}
