<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Auth;
use App\Transformers\UserTransformer;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                fractal(Auth::user(), new UserTransformer())->toArray()['data']
            ]);
        } else {
            return response()->json([
                'errors' => ['Login failed']
            ], 422);
        }
    }
}
