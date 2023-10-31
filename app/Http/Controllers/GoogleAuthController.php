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
use Mail;
use App\Models\Template;

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
            
            $userExist = User::where('email',$user->email)->first();

            $url = env('APP_URL_WEB_PAGE');
            
            if($userExist){
                $token = JWTAuth::fromUser($userExist);

                return redirect($url)->with('token');
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

                $template = Template::where('title','After Signed Up')->first();

                if($template->subject == 'default'){
                    $subject = 'Tamice Sign Up ';
                } else {
                    $subject = $template->subject;
                }
                
                
                Mail::send('email.notificationAfterRegistered', ['fullname' => $userNew->name, 'template' => $template], function($message) use($userNew, $template,$subject){
                    $message->to($userNew->email);
                    $message->subject($subject);
                });

                return redirect($url)->with('token');
            }

        } catch (Exception $e) {
            \Log::debug($e);
            return redirect('google-login');
        }
    }
}
