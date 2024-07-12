<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckTemporaryUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Auth::logout();
        // session()->remove('temp_user_id');

        if (!Auth::check()) {
            if (!session()->has('temp_user_id')) {
                $user = User::create([
                    'id_user' => (string) Str::uuid(),
                ]);

                session(['temp_user_id' => $user->id_user]);

                Auth::login($user, true);
            } else {
                $tempUserId = session('temp_user_id');
                $user = User::find($tempUserId);
                if ($user) {
                    Auth::login($user, true);
                }
            }
        }

        return $next($request);
    }
}
