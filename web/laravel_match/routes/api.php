<?php

use App\Project;
use Illuminate\Http\Request;

// ***** 認証 *****
Route::post('/register', 'Auth\RegisterController@register')->name('register'); // ユーザー登録
Route::put('/users/{id}', 'UserController@update')->name('users.update'); // ユーザー情報の更新
Route::post('/login', 'Auth\LoginController@login')->name('login'); // ログイン
Route::post('/logout', 'Auth\LoginController@logout')->name('logout'); // ログアウト
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email'); // パスワードリセット用のメールを送信
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset'); // パスワードリセット用のフォームを表示
Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.update'); // パスワードリセット
Route::get('/user/info', fn() => Auth::user())->name('user'); // ログインユーザーの返却

// ***** マイページ *****
Route::get('/mypage', 'MypageController@index')->name('mypage'); // 案件一覧表示

// ***** 案件 *****
Route::get('/projects/{id}/edit', 'ProjectController@edit')->name('projects.edit'); // 案件編集画面の表示
Route::post('/projects/register', 'ProjectController@create')->name('project.create'); // 案件登録
Route::put('/projects/{id}', 'ProjectController@update')->name('projects.update'); // 案件更新
Route::get('/project/info', function (Project $project) { return $project; })->name('project'); // プロジェクト情報の返却
Route::get('/projects/list', 'ProjectController@index')->name('projects.index'); // 案件一覧表示

// ***** 案件詳細 *****
Route::get('/project/detail/{id}', 'ProjectDetailController@show')->name('projectDetail.show'); // 案件詳細画面の表示
//Route::post('/project', 'ProjectDetailController@update')->name('project.update'); // 案件詳細画面の更新 *「応募」と「パブリックメッセージ投稿」がある
Route::post('/project/detail/{id}', 'ProjectDetailController@create')->name('projectDetail.create');
Route::put('/project/detail/{id}', 'ProjectDetailController@update')->name('projectDetail.update');

// ***** メッセージ



// ***** 削除候補 *****

// apiの場合いらない。これがたたかれ、router.jsで捉えられないとjsonの値がそのまま表示されてしまいそう
// Route::get('/users/:userId/edit', 'UserController@edit')->name('users.edit'); // ユーザー編集画面の表示

// 案件の場合はいるのかなぁ...?
// Route::get('/projects/{id}/edit', 'ProjectController@edit')->name('projects.edit'); // 案件編集画面の表示

// :userId は間違いっぽい。一度は動作していたが、405 Method not allowed になってしまった。ProjectContoroller@update作成をきっかけに{id}に修正
//Route::put('/users/:userId', 'UserController@update')->name('users.update'); // ユーザー情報の更新
