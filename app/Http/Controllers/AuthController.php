<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate(
            [
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string',
                'dob' => 'required|date',
            ]
        );
        $user = User::create(
            [
                'name' => $fields['name'],
                'email' => $fields['email'],
                'password' => bcrypt($fields['password']),
                'dob' => $fields['dob']
            ]
        );

        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate(
            [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]
        );

        $user = User::whereEmail($fields['email'])->first();
        //Make sure the credentials are correct
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response(
                [
                    'message' => 'Wrong Credentials'
                ],
                401
            );
        }

        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Successfully logged out'
        ];
    }
}
