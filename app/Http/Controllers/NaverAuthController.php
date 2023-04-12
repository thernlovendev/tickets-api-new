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

class NaverAuthController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToNaver()
    {
        return Socialite::driver('naver')->redirect();
    }

    public function handleNaverCallback()
    {
        try {
            
            $user = Socialite::driver('naver')->user();
            $userResponse = $user['response'];

            $userExist = User::where('email',$user->email)->where('external_auth','naver')->first();
            if($userExist){
                $token = JWTAuth::fromUser($userExist);
                
                return view('/welcome',compact('token'));
            } else {
                $userNew = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $userResponse['profile_image'],
                    'phone' => $userResponse['mobile'],
                    'external_id' => $user->id,
                    'external_auth' => 'naver',
                ]);

                $userNew->assignRole('customer');

                $token = JWTAuth::fromUser($userNew);

                return view('/welcome',compact('token'));
            }

        } catch (Exception $e) {
            \Log::debug($e);
            return redirect('naver-login');
        }
    }
}
