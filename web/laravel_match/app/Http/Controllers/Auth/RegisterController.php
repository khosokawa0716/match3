<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'min:3', 'max:10', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:16', 'confirmed'],
            'profile_fields' => ['nullable','string', 'max:120']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // 入力が任意の項目は、未入力時にはnullをDBに書き込む
        if ( $data['file'] ) {
            // アイコン画像のファイル名は、重複を避けるために「登録日時+元のファイル名」
            $file_name = time() . '.' . $data['file']->getClientOriginalName();
            $data['file']->storeAs('public', $file_name);
            $icon_path = env('APP_URL').'/storage/' . $file_name;
        } else {
            // 画像の選択がない場合には、ゲストのアイコン画像をあてる
            $icon_path = env('APP_URL').'/storage/guest.png';
        }

        if ( $data['profile_fields'] ) {
            $profile_fields = $data['profile_fields'];
        } else {
            $profile_fields = null;
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'icon_path' => $icon_path,
            'profile_fields' => $profile_fields
        ]);
    }

    // メソッド追加 リダイレクトさせるのでなくユーザー情報を返却する
    protected function registered(Request $request, $user)
    {
        return $user;
    }
}
