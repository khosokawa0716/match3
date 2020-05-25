<?php

use App\Project;
use Illuminate\Http\Request;

// ***** 認証 *****
Route::post('/register', 'Auth\RegisterController@register')->name('register'); // ユーザー登録
Route::get('/users/{id}/edit', 'UserController@edit')->name('users.edit'); // ユーザー情報編集画面の表示
Route::put('/users/{id}', 'UserController@update')->name('users.update'); // ユーザー情報の更新
Route::post('/login', 'Auth\LoginController@login')->name('login'); // ログイン
Route::post('/logout', 'Auth\LoginController@logout')->name('logout'); // ログアウト
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email'); // パスワードリセット用のメールを送信
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset'); // パスワードリセット用のフォームを表示
Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.update'); // パスワードリセット
Route::get('/user/info', function () { return Auth::user(); })->name('user'); // ログインユーザーの返却

// ***** マイページ *****
Route::get('/mypage', 'MypageController@index')->name('mypage'); // 案件一覧表示

// ***** 案件 *****
Route::get('/projects/{id}/edit', 'ProjectController@edit')->name('projects.edit'); // 案件編集画面の表示
Route::post('/projects/register', 'ProjectController@create')->name('project.create'); // 案件登録
Route::put('/projects/{id}/edit', 'ProjectController@update')->name('projects.update'); // 案件更新
Route::get('/project/info', function (Project $project) { return $project; })->name('project'); // プロジェクト情報の返却
Route::get('/projects/list', 'ProjectController@index')->name('projects.index'); // 案件一覧表示

// ***** 案件詳細 *****
Route::get('/project/detail/{id}', 'ProjectDetailController@show')->name('projectDetail.show'); // 案件詳細画面の表示
Route::post('/project/detail/{id}', 'ProjectDetailController@create')->name('projectDetail.create'); // 案件詳細画面でのパブリックメッセージ投稿
Route::put('/project/detail/{id}', 'ProjectDetailController@update')->name('projectDetail.update'); // 案件詳細画面での応募

// ***** メッセージ
Route::get('/public_messages/list', 'PublicMessagesController@show')->name('publicMessages.show'); // パブリックメッセージ一覧の表示
Route::get('/private_messages/list', 'PrivateMessagesController@show')->name('privateMessages.show'); // プライベートメッセージ一覧の表示
Route::get('/private_messages/detail/{id}', 'PrivateMessagesDetailController@show')->name('privateMessagesDetail.show'); // プライベートメッセージ詳細の表示
Route::post('/private_messages/detail/{id}', 'PrivateMessagesDetailController@create')->name('privateMessagesDetail.create'); // プライベートメッセージの投稿

// トークンリフレッシュ
Route::get('/reflesh-token', function (Illuminate\Http\Request $request) {
    $request->session()->regenerateToken();

    return response()->json();
});

// ***** 削除候補 *****

// :userId は間違いっぽい。一度は動作していたが、405 Method not allowed になってしまった。ProjectContoroller@update作成をきっかけに{id}に修正
//Route::put('/users/:userId', 'UserController@update')->name('users.update'); // ユーザー情報の更新
