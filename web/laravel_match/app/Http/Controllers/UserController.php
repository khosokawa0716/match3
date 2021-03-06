<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */

    public function edit($id) // 引数Users $userを削除
    {
        $user = User::find($id);
        return $user;
    }

    /**
     * ユーザー情報の更新
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return Array
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id); // $idはusersテーブルのid

        // バリデーションチェック
        $request->validate([
            'email' => 'required|string|email|max:255',
            'profile_fields' => 'nullable|string|max:120'
        ]);

            if ( $user->email !== $request['email'] ) {
                $this->validate($request, [
                    // ユニークのチェックは、変更がある場合のみおこなう。
                    // そうしないと、現在登録している自分のメールアドレスと更新するためのデータがぶつかりエラーになってしまうため
                    'email' => 'unique:users'
                ]);
                $user->email = $request['email'];
            }

            if ( $request['file'] ) {
                // アイコン画像のファイル名は、重複を避けるために「登録日時+元のファイル名」
                $file_name = time().'.'.$request['file']->getClientOriginalName();
                $request['file']->storeAs('public', $file_name);
                $user->icon_path = env('APP_URL').'/storage/'.$file_name;
            }

            if ( $user->profile_fields !== $request['profile_fields'] ) {
                $user->profile_fields = $request['profile_fields'];
            }

        $user->save();
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
