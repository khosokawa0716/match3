<?php

use Illuminate\Http\Request;

// ***** 認証 *****
Route::post('/register', 'Auth\RegisterController@register')->name('register'); // ユーザー登録
Route::get('/users/:userId/edit', 'UserController@edit')->name('users.edit'); // ユーザー編集画面の表示
Route::put('/users/:userId', 'UserController@update')->name('users.update'); // ユーザー情報の更新
Route::post('/login', 'Auth\LoginController@login')->name('login'); // ログイン
Route::post('/logout', 'Auth\LoginController@logout')->name('logout'); // ログアウト
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email'); // パスワードリセット用のメールを送信
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset'); // パスワードリセット用のフォームを表示
Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.update'); // パスワードリセット
Route::get('/user/info', fn() => Auth::user())->name('user'); // ログインユーザーの返却

// ***** 案件 *****
Route::post('/projects/register', 'ProjectController@register')->name('projects.register'); // 案件登録
Route::get('/projects', 'ProjectController@index')->name('projects.index'); // 案件一覧表示
Route::get('/projects/edit/{project}', 'ProjectController@edit')->name('projects.edit'); // 案件編集画面の表示
Route::put('/projects/edit', 'ProjectController@update')->name('projects.update'); // 案件更新

// ***** 案件詳細 *****
Route::get('/project/{project}', 'ProjectDetailController@show')->name('project.show'); // 案件詳細画面の表示
Route::post('/project', 'ProjectDetailController@update')->name('project.update'); // 案件詳細画面の更新 *「応募」と「パブリックメッセージ投稿」がある

// ***** メッセージ
