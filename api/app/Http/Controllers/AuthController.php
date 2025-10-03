<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Utils\ValidatorRequest;
use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Cria objeto de validação
        $validate = new ValidatorRequest($request, [
            'email' => 'required|string|max:255|email',
            'password' => ['required', 'string'],
        ]);

        // Valida se dados enviados batem com o objeto, caso não, dispara erro
        $error = $validate->handleErrors();
        if ($error)
            return $error;

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials))
            return response()->json(['message' => 'Invalid credentials'], 401);

        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        // Retorna resposta de sucesso
        return response()->json([
            'message' => 'Login successful!',
            'token' => $token
        ], 200);
    }

    public function guest()
    {
        $user = User::create([
            'id_user' => (string) Str::uuid(),
            'is_guest' => true,
        ]);

        $token = $user->createToken('guest')->plainTextToken;

        return response()->json([
            'message' => 'Guest created successfully!',
            'token' => $token
        ], 201);
    }
}
