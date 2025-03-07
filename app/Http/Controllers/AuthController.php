<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegistroRequest;


class AuthController extends Controller
{


    public function register(RegistroRequest $request)
    {
        $data = $request->validated();
        //Crear al usuario

        $user = User::create(
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password'])
            ]
        );

        //retorna una respuesta

        return [
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ];
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        //Revision del password
        if (!Auth::attempt($data)) {
            return response([
                'errors' => [
                    'El emial o password son incorrectos'
                ]
            ], 422);
        }
        //Autenticar al usuario
        $user = Auth::user();
        return [
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ];
    }

    public function logout(Request $request)
{
    if ($request->user()) {
        $request->user()->currentAccessToken()->delete();
    }

    return [
        'user' => null
    ];
}

}
