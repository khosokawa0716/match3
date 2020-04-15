<?php

use Illuminate\Http\Request;

// ユーザー登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');

// ユーザー情報の取得
Route::get('/users/{user}/edit', 'UserController@edit')->name('users.edit');

// ユーザー情報の更新
//Route::post('/users', 'UserController@update')->name('users.update');
//Route::post('/users/{user}', 'UserController@update')->name('users.update');
//Route::patch('/users/{user}', 'UserController@update')->name('users.update');
//Route::patch('/users', 'UserController@update')->name('users.update');
Route::put('/users/{user}', 'UserController@update')->name('users.update');

//Route::resource('users', 'UserController');

// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');

// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// パスワードリセット用のメールを送信
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

// パスワードリセット用のフォームを表示
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

// パスワードリセット
Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
