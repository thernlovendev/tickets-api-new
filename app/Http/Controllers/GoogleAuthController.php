<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Auth;
use Exception;
use JWTAuth;
use App\Models\User;

class GoogleAuthController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    public function handleGoogleCallback()
    {
        try {
            
            $user = Socialite::driver('google')->user();
            
            $userExist = User::where('email',$user->email)->where('external_auth','google')->first();
            
            if($userExist){
                $token = JWTAuth::fromUser($userExist);

                return view('/welcome',compact('token'));
            } else {
                $userNew = User::create([
                    'name' => $user->name,
                    'firstname' => $user->user['given_name'],
                    'lastname' => $user->user['family_name'],
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'external_id' => $user->id,
                    'external_auth' => 'google',
                ]);
                
                $userNew->assignRole('customer');
                
                $token = JWTAuth::fromUser($userNew);

                return view('/welcome',compact('token'));
            }

        } catch (Exception $e) {
            \Log::debug($e);
            return redirect('google-login');
        }
    }
}
