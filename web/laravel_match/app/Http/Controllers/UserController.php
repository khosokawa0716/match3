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
    public function edit(User $user)
    {
        return $user;
    }

    // 実際の更新処理
    // 終わったら、そのユーザのページへ移動
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
//        ini_set('memory_limit', '256M');
//        Log::debug(print_r($request, true));
        // $requestをログ出力させようとすると、メモリが128Mでは足りなくなるので注意...

//        $user->email = $request->email;
//        $user->profile_fields = $request->profile_fields;
        // this.editFormのまま値を渡すときに上記の書き方になる

        // アイコン画像のファイル名は、重複を避けるために「登録日時+元のファイル名」
        $file_name = time().'.'.$request['file']->getClientOriginalName();
        $request['file']->storeAs('public', $file_name);

        $user->email = $request['email'];
        $user->icon_path = 'storage/'.$file_name;
        $user->profile_fields = $request['profile_fields'];
        $user->save();

        return $user;
//        return redirect('users/'.$user->id);
    }
//    /**
//     * Update the specified resource in storage.
//     *
//     * @param  array $data
//     * @return \App\User
//     */
//    public function update(array $data)
//    protected function update(array $data)
//    {
//        Log::debug('UserController.php/update起動');
        // アイコン画像のファイル名は、重複を避けるために「登録日時+元のファイル名」
//        $file_name = time().'.'.$data['file']->getClientOriginalName();
//        $data['file']->storeAs('public', $file_name);
//
//        $user = \App\User::findOrFail($data['id']);
//
//        $user->email = $data['email'];
//        $user->icon_path = 'storage/'.$file_name;
//        $user->profile_fields = $data['profile_fields'];
//        $user->save();
//
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
