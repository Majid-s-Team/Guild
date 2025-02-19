<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{
    public function index()
    {
          $user = auth::user();

          return new UserResource($user);

          /*if($user->user_type == 'admin') {
              return new PrivateUserResource($user);
          } else {
              return new UserResource($user);
          }*/
    }

    public function apilimit(Request $request)
    {

        /*if (RateLimiter::tooManyAttempts('send-message:'.$request->user()->id, $perMinute = 5)) {
            $seconds = RateLimiter::availableIn('send-message:'.$request->user()->id);
            return response()->json(['message' => 'Too many requests. Please try again later.'. $seconds], 429);
        }

        RateLimiter::hit('send-message:'.$request->user()->id);*/

        //\DB::table('test')->insert(['name'=>1]);
    }
}
