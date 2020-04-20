<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
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
}
