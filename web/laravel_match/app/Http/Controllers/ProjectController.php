<?php

namespace App\Http\Controllers;

use App\Application;
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

    /**
     * 表示する案件をとってくる
     * @param \Illuminate\Http\Request $request
     * @return Array
     */
    public function index(Request $request){
        // 絞り込み条件を引数$requestに含む
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

    /**
     * 案件の登録
     * @param \Illuminate\Http\Request $request
     * @param \App\Project $project
     * @return Array
     */
    public function create(Request $request, Project $project) {
        $request->validate([
            'title' => 'required|string|min:3|max:60',
            'type' => 'required',
            'minimum_amount' => 'nullable|integer|min:1000|max:10000000',
            'max_amount' => 'nullable|integer|min:1000|max:10000000|gte:minimum_amount',
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

    /**
     * 表示する案件をとってくる
     * @param int $data
     * @return Array
     */
    public function edit($data)
    {
        $user_id = Auth::id();
        $project_id = $data; // $dataはprojectsのid
        if (ctype_digit($project_id)) {
            $project = Project::find($project_id);
            // 検索結果がない場合には、エラーコード404を返却する
            if ($project === null) { return abort(404); }

            // 他人が登録した案件を更新しようとした場合、エラーコード403を返却する
            if ($project->user_id !== $user_id) { return abort(403); }

            // すでに応募がある案件かどうか
            // フロント側でreadonlyの表示・非表示の切り替えに使う
            $is_applied = Application::where('project_id', $project_id)
                ->exists();

            return [
                'project' => $project,
                'is_applied' => $is_applied
                ];
        } else {
            // URLのID部分に数値でない入力でリクエストがあった場合にも、エラーコード404を返却する
            return abort(404);
        }
    }

    /**
     * 案件の更新
     * @param \Illuminate\Http\Request $request
     * @param int $data
     * @return Array
     */
    public function update (Request $request, $id)
    {
        $user_id = Auth::id();
        $project_id = $id; // $dataはprojectsのid

        // 1.この操作をする権限があるかどうかを確認する
        if (ctype_digit($project_id)) {
            $project = Project::find($project_id);
            // 検索結果がない場合には、エラーコード404を返却する
            if ($project === null) { return abort(404); }

            // 他人が登録した案件を更新しようとした場合、エラーコード403を返却する
            if ($project->user_id !== $user_id) { return abort(403); }
        } else {
            // URLのID部分に数値でない入力でリクエストがあった場合にも、エラーコード404を返却する
            return abort(404);
        }

        // 2.バリデーションチェック
        $request->validate([
            'title' => 'required|string|min:3|max:60',
            'status' => 'required',
            'type' => 'required',
            'minimum_amount' => 'nullable|integer|min:1000|max:10000000',
            'max_amount' => 'nullable|integer|min:1000|max:10000000|gte:minimum_amount',
            'detail' => 'required|string|min:3|max:1000'
        ]);

        // 3.変更がある項目のみセットする
        if ( $project->title !== $request['title'] ) { $project->title = $request['title']; }
        if ( $project->status !== $request['status'] ) { $project->status = $request['status']; }
        if ( $project->type !== $request['type']) { $project->type = $request['type']; }
        if ( $project->minimum_amount !== $request['minimum_amount'] ) { $project->minimum_amount = $request['minimum_amount']; }
        if ( $project->max_amount !== $request['max_amount'] ) { $project->max_amount = $request['max_amount']; }
        if ( $project->detail !== $request['detail'] ) { $project->detail = $request['detail']; }

        // 4.DBに保存して、$projectを返却する
        $project->save();
        return $project;
    }
}
