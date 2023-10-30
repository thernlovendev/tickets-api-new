<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon; 
use App\Models\User; 
use DB; 
use Mail; 
use Hash;
use Illuminate\Support\Str;
use Validator;
use App\Models\PasswordReset;
use App\Models\Template;

use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
      {
          $validator = Validator::make($request->all(), [
            'email'=>'required|email|exists:users',
        ], [
            'exists' => __('The email is invalid')
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all(), 400);
        } 
        $user = User::where('email',$request->input('email'))->first();
        $token = Str::random(64);
        $url = env('APP_URL');

        DB::table('password_resets')->where(['email'=> $request->email])->delete();
        
        $password_reset =  DB::table('password_resets')->insert([
            'email' => $request->email, 
            'token' => $token, 
            'created_at' => Carbon::now()
          ]);

        $template = Template::where('title','After Password Reset Request By User')->first();

        if($template->subject == 'default'){
            $subject = 'Reset Password';
        } else {
            $subject = $template->subject;
        }
        \Log::debug('forget'.$token);
        
        // Mail::send('email.forgetPasswordResetRequestByUser', ['token' => $token, 'fullname' => $user->name, 'url' => $url, 'template' => $template,'email' => $request->input('email')], function($message) use($request, $template,$subject){
        //     $message->to($request->email);
        //     $message->subject($subject);
        // });
        

            
            return response()->json(['message', 'We have e-mailed your password reset link!']);
        }

    public function submitResetPasswordForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users',
              'password' => 'required|string|min:6|confirmed',
              'password_confirmation' => 'required'
          ]);
  
          $updatePassword = DB::table('password_resets')
                              ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                              ])
                              ->first();
  
          if(!$updatePassword){
              return back()->withInput()->with('error', 'Invalid token!');
          }
  
          $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]);
 
          DB::table('password_resets')->where(['email'=> $request->email])->delete();
  
          $template = Template::where('title','After Password Reset')->first();
        
            if($template->subject == 'default'){
                $subject = "Password Reset successful";
            } else {
                $subject = $template->subject;
            }
            $user_email =  User::find($user);
            
            // Mail::send('email.notificationAfterPasswordReset', ['fullname' => $user_email->name, 'template' => $template], function($message) use($user_email, $template, $subject){
            //     $message->to($user_email->email);
            //     $message->subject($subject);
            // });
            
            
          $url = env('APP_URL_WEB_PAGE');
          return response()->json(['message', 'Your password saved successful']);
      }
    }
