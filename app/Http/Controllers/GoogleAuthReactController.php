<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Auth;
use Exception;
use JWTAuth;
use App\Models\User;
use Mail;
use App\Models\Template;

class GoogleAuthReactController extends Controller
{
    use AuthenticatesUsers;

    public function handleGoogleAuth(Request $request)
    {
        $token = $request->input('token'); 

       
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_OAUTH_ID'));
        try {
            $payload = $client->verifyIdToken($token);
            if ($payload) {

                $email = $payload['email'];
                $userExist = User::where('email',$email)->first();
                if($userExist){
                    $token = JWTAuth::fromUser($userExist);
                    $role = $userExist->roles->first();
                    
                    return response()->json(['token' => $token, 'role' => $role]);
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
    
                    $role = $userNew->roles->first();
    
                    return response()->json(['token' => $token, 'role' => $role]);
                }
            }
        } catch (Exception $e) {
            // Ocurrió un error en la verificación del token
            return response()->json(['error' => 'Invalid Token'], 401);
        }
    }

    
}
