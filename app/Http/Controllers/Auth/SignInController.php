<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInRequest;
use App\Http\Responses\ApiErrorResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignInController extends Controller
{
    public function SignIn(SignInRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = auth()->user()->createToken('MyApp')->accessToken;
            $user = auth()->user();

            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'super_admin' => $user->super_admin,
                    'image' => url($user->image),
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_type' => $user->user_type,
                    'roles' => $user->getRoleNames(),
                    'permissions' => $user->getAllPermissions()->pluck('name'),
                ],

            ], 200);
        } else {
            return new ApiErrorResponse('Please check your email & password!', '', 401);
        }

    }

    public function UserSignIn(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = auth()->user()->createToken('MyApp')->accessToken;
            $user = auth()->user();

            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'image' => url($user->image),
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_type' => $user->user_type
                ],

            ], 200);
        } else {
            return new ApiErrorResponse('Please check your email & password!', '', 401);
        }
    }
}
