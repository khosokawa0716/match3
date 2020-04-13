<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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

    protected function rules()
    {
        // パスワード再設定時にメールアドレスの入力が不要になるようにオーバーライド
        return [
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ];
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('index')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
}
