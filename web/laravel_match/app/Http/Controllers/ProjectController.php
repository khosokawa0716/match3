<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth')->except(['index']);
    }

    public function index(){
        Log::info('ProjectControllerのindex起動');
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

    // 多分このコントローラいらない ルーティングから削除しても動作するから
    public function edit($data)
    {
        $id = $data;
        Log::info('ProjectControllerのedit起動');
//        Log::info(print_r($data, true));
        Log::info('$idの値: '.$id);
        if (ctype_digit($id)) {
            $project = Project::find($id);
            Log::info(print_r($project, true));
            return $project;
        }
    }

    public function update (Request $request, $id) // 引数Project $project を削除
    {
        Log::info('ProjectControllerのupdate起動');
        $project = Project::find($id);

        // 入力された項目だけ更新をおこなう
        // 入力項目の有無に関わらずユーザー情報を返却する
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
