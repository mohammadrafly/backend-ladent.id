<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function user(Request $request)
    {
        $authHeader = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $tokenInstance = \Laravel\Sanctum\PersonalAccessToken::findToken($token);

        if ($tokenInstance) {
            return new AuthResource(true, 'User authenticated', $tokenInstance);
        } else {
            return new AuthResource(false, 'Invalid token', null);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'boolean',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        $user = User::where('email', $credentials['email'])->first();

        if (Auth::attempt($credentials, $remember)) {
            $token = $user->createToken($request->email)->plainTextToken;
            $tokenInstance = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
            $data = [
                'token' => $token,
                'name' => $tokenInstance->name,
            ];
            return new AuthResource(true, 'Login successful.', $data);
        }

        return new AuthResource(false, 'Invalid credentials.', null);
    }

    public function logout(Request $request)
    {
        $authHeader = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $tokenInstance = \Laravel\Sanctum\PersonalAccessToken::findToken($token);

        if ($tokenInstance) {
            $tokenInstance->delete();
            return new AuthResource(true, 'Berhasil logout!', null);
        } else {
            return new AuthResource(false, 'Token tidak ditemukan.', null);
        }
    }
}
