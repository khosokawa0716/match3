<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    // 多分このコントローラいらない ルーティングから削除しても動作するから
//    public function edit(User $user)
//    {
//        Log::info('UserControllerのedit起動');
//        return $user;
//    }

    // 実際の更新処理
    // 終わったら、そのユーザのページへ移動
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) // 引数Users $userを削除
    {
        Log::info('UserControllerのupdate起動');
        $user = User::find($id);

        // 入力された項目だけ更新をおこなう
        // 入力項目の有無に関わらずユーザー情報を返却する
        if ( $request->filled('email') | $request->filled('file') | $request->filled('profile_fields')) {

            if ( $request->filled('email') ) {
                $this->validate($request, [
                    'email' => 'email|max:100'
                ]);
                $user->email = $request['email'];
            }

            if ( $request->filled('file') ) {
                // アイコン画像のファイル名は、重複を避けるために「登録日時+元のファイル名」
                $file_name = time().'.'.$request['file']->getClientOriginalName();
                $request['file']->storeAs('public', $file_name);
                $user->icon_path = env('APP_URL').'/storage/'.$file_name;
            }

            if ( $request->filled('profile_fields') ) {
                $this->validate($request, [
                    'profile_fields' => 'string|max:2550'
                ]);
                $user->profile_fields = $request['profile_fields'];
            }
            $user->save();
        }

        return $user;
    }

//    public function rules() {
//        return [
//            'email' => 'email|max:100',
            // ユニーク。ただし、自分のメールアドレス自体はエラーにならない
//            'email' => 'unique:users,email,'.$this->me->id,
//            'profile_fields' => 'max:500',
//        ];
//    }

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
