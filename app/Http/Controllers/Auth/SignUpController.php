<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignUpController extends Controller
{
    public function register(SignupRequest $request)
    {
        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'source' => $request->source,
                'is_active' => true
            ]);

            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Registration failed',
                'details' => $e->getMessage()
            ], 422);
        }
    }

    public function signupComplete(Request $request)
    {
        $data = $request->validate([
            'heard_about_us' => 'nullable',
            'nationality' => 'required',
            'resident_country' => 'required',
            'terms_accepted' => 'required|boolean',
            'dob' => 'required|date',
        ]);

        $data['residence_country'] = $data['resident_country'];
        $data['how_hear_about_us'] = $data['heard_about_us'];



        //session(['user_id' => 1]);

        /*$userId = session('user_id');

        if (!$userId) {
            return response()->json(['message' => 'Record not found'], 404);
        }*/

        $user = User::findOrFail($request->userId);
        $user->update($data);

       // session()->forget('user_id');

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number
            ]
        ]);
    }
}
