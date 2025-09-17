<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Login para API, retorna token y datos del usuario
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        // Crear token con Sanctum
        $token = $user->createToken('api_token')->plainTextToken;

        // Versión 1: Retornar token en JSON
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);

        /*
        // Versión 2: Guardar token en cookie segura
        use Illuminate\Support\Facades\Cookie;
        return response()->json([
            'user' => $user,
            'token' => $token,
        ])->withCookie(
            cookie(
                'api_token', // nombre de la cookie
                $token,      // valor
                60*24,       // duración en minutos (1 día)
                '/',         // path
                null,        // dominio
                true,        // Secure (solo HTTPS)
                true,        // HttpOnly (no accesible por JS)
                false,       // Raw
                'Strict'     // SameSite
            )
        );
        */
    }

    // Logout para API
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }
}
