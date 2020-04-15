<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
//            return redirect('/home'); // デフォルト
            // ログイン状態で非ログイン状態でのみアクセスできる機能にリクエストを送信した場合にHTMLを返却させない
            return redirect()->route('user');
        }

        return $next($request);
    }
}
