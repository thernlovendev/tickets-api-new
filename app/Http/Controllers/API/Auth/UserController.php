<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;
use Log;
use App\Http\Requests\UserRequest;
use Redirect;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
      $credentials = $request->only('email', 'password');
      try {
          if (! $token = JWTAuth::attempt($credentials)) {
              return response()->json(['error' => 'invalid_credentials'], 400);
          }
      } catch (JWTException $e) {
          return response()->json(['error' => 'could_not_create_token'], 500);
      }
      return response()->json(compact('token'));
    }

    public function getAuthenticatedUser()
    {
        try {
          if (!$user = JWTAuth::parseToken()->authenticate()) {
                  return response()->json(['user_not_found'], 404);
          }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }


    public function register(UserRequest $request)
    {

        $user = User::create([
            'name' => $request->get('firstname').' '.$request->get('lastname'),
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'phone' => $request->get('phone'),
        ]);


        $user->assignRole('customer');
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function getProfile(Request $request)
    {
        return response()->json(auth()->user());
    }

    public function updateProfile(Request $request)
    {   
        $request->validate([
            'firstname' => 'min:2| max:55',
            'lastname' => 'min:2| max:55',
            'email' => 'email',
            'phone' => 'max:15',
        ]);

        $user = auth()->user();

        $user->update([
            'firstname' => $request->firstname !== null ? $request->firstname : $user->firstname,
            'lastname' => $request->lastname !== null ? $request->lastname.' '.$request->lastname : $user->lastname,
            'email' => $request->email !== null ? $request->email: $user->email,
            'phone' => $request->phone !== null ? $request->phone : $user->phone,
            'updated_at' => now(),
        ]);

        $user->save();

        return response()->json([
            'message' => 'Profile Update',
        ]);
    }

    public function logout()
    {
        auth()->logout();
        
        return response()->json([
            'message' => 'User logged out',
        ]);
    }

    public function deleteMyUser(Request $request){
        $myUser = auth()->user();
        auth()->logout();
        User::find($myUser->id)->delete();
        
        return Redirect::route('home')->with('message', 'Your account has been deleted!');
    }
}
