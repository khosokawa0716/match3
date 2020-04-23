<?php

namespace App\Http\Controllers;

use App\Project;
use App\PublicMessage;
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

    public function show($data)
    {
        $id = $data;
//        Log::info('ProjectDetailControllerのshow起動');
        if (ctype_digit($id)) {
            $project = Project::where('id', $id)->with(['owner'])->first();
            $public_messages = PublicMessage::where('project_id', $id)->with(['author'])->orderBy(PublicMessage::CREATED_AT, 'desc')->get();
            return [ 'project' => $project, 'public_messages' => $public_messages ];
        }
    }

    public function create(Request $request, PublicMessage $publicMessage, $data)
    {
//        Log::info(print_r($data, true));
//        Log::info(print_r($request['content'], true));
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
}