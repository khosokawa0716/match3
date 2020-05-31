<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth')->except(['index']);
    }

    // 表示する案件をとってくる
    // 絞り込み条件を引数$requestに含む
    public function index(Request $request){
        $status = $request['status'];
        $type = $request['type'];

        $projects = Project::with(['owner'])
            ->where( function($query) use($type) { // typeがallでないときに、このwhere句を有効にする
                if ($type !== 'all') {
                    $query->where('type', $type);
                }
            })
            ->where( function($query) use($status) { // statusが2（すべて）でないときに、このwhere句を有効にする
                if ($status !== "2") {
                    $query->where('status', $status);
                }
            })
            ->latest()
            ->paginate();

        return $projects;
    }

    // 案件の登録
    public function create(Request $request, Project $project) {
        $request->validate([
            'title' => 'required|string|min:3|max:20',
            'type' => 'required',
            'minimum_amount' => 'nullable|integer|min:1000|max:10000000',
            'max_amount' => 'nullable|integer|min:1000|max:10000000',
            'detail' => 'required|string|min:3|max:1000'
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
        if (ctype_digit($project_id)) {
            $project = Project::find($project_id);
            // 検索結果がない場合には、エラーコード404を返却する
            if ($project === null) { return abort(404); }

            // 以下2つのケースでエラーコード403を返却する
            // 1.応募が終了した案件を編集しようとした
            // 2.他人が登録した案件を編集しようとした
            if ($project->status === 0 || $project->user_id !== $user_id) { return abort(403); }

            return $project;
        } else {
            // URLのID部分に数値でない入力でリクエストがあった場合にも、エラーコード404を返却する
            return abort(404);
        }
    }

    // 案件の更新。引数は、$request編集:フォームに入力された値と $id:案件のid
    public function update (Request $request, $id)
    {
        $user_id = Auth::id();
        $project_id = $id;

        // 1.この操作をする権限があるかどうかを確認する
        if (ctype_digit($project_id)) {
            $project = Project::find($project_id);
            // 検索結果がない場合には、エラーコード404を返却する
            if ($project === null) { return abort(404); }

            // 以下2つのケースでエラーコード403を返却する
            // ・応募が終了した案件を更新しようとした
            // ・他人が登録した案件を更新しようとした
            if ($project->status === 0 || $project->user_id !== $user_id) { return abort(403); }
        } else {
            // URLのID部分に数値でない入力でリクエストがあった場合にも、エラーコード404を返却する
            return abort(404);
        }

        // 2.バリデーションチェック
        $request->validate([
            'title' => 'required|string|min:3|max:20',
            'type' => 'required',
            'minimum_amount' => 'nullable|integer|min:1000|max:10000000',
            'max_amount' => 'nullable|integer|min:1000|max:10000000',
            'detail' => 'required|string|min:3|max:1000'
        ]);

        // 3.変更がある項目のみセットする
        if ( $project->title !== $request['title'] ) { $project->title = $request['title']; }
        if ( $project->type !== $request['type']) { $project->type = $request['type']; }
        if ( $project->minimum_amount !== $request['minimum_amount'] ) { $project->minimum_amount = $request['minimum_amount']; }
        if ( $project->max_amount !== $request['max_amount'] ) { $project->max_amount = $request['max_amount']; }
        if ( $project->detail !== $request['detail'] ) { $project->detail = $request['detail']; }

        // 4.DBに保存して、$projectを返却する
        $project->save();
        return $project;
    }
}
