<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function detail($email)
    {
        $user = User::where('email', $email)->first();
        return new UserResource(true, 'User ditemukan.', $user);
    }
}
