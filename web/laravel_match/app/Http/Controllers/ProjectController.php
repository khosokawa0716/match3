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

    public function edit($id)
    {
        Log::info('ProjectControllerのedit起動');
        if (ctype_digit($id)) {
            $project = Project::find($id);
            Log::info(print_r($project, true));
            return $project;
        }
    }

    public function update (Request $request, Project $project, $id)
    {
        Log::info('ProjectControllerのupdate起動');
        $project = Project::find($id);

        $project->title = $request['title'];

        $project->save();

        return $project;
    }
}
