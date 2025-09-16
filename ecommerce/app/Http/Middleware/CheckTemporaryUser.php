<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

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
        Log::info('', ['Auth::check()' => Auth::check()]);

        if (!Auth::check()) {
            // Se não tiver um usuário autenticado, cria um novo usuário anônimo
            if (!session()->has('temp_user_id')) {
                // Cria um novo usuário anônimo
                $user = User::create([
                    'id_user' => (string) Str::uuid(),
                ]);

                // Grava o ID temporário na sessão
                session(['temp_user_id' => $user->id_user]);

                // Faz o login com o usuário temporário
                Auth::login($user, true);

                Log::info('Nenhum usuário autenticado, criando um novo usuário anônimo.', ['user_id' => $user->id_user]);
            } else {
                // Recupera o usuário temporário da sessão, se houver
                $tempUserId = session('temp_user_id');
                $user = User::find($tempUserId);

                if ($user) {
                    // Loga novamente o usuário temporário
                    Auth::login($user, true);
                    Log::info('Usuário temporário logado novamente.', ['user_id' => $user->id_user]);
                } else {
                    // Caso o usuário da sessão não exista mais, cria um novo
                    $user = User::create([
                        'id_user' => (string) Str::uuid(),
                    ]);

                    session(['temp_user_id' => $user->id_user]);
                    Auth::login($user, true);

                    Log::info('Criando novo usuário temporário porque o usuário da sessão não foi encontrado.', ['user_id' => $user->id_user]);
                }
            }
        }

        // Continua para a próxima etapa da requisição
        return $next($request);
    }
}
