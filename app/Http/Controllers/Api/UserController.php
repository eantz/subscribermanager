<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Transformers\UserTransformer;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::guard('api')->user();

        return response()->json([
            fractal($user, new UserTransformer())->toArray()['data']
        ]);
    }
}
