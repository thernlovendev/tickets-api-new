<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestorePasswordController extends Controller
{
    public function showResetPasswordForm($token,$email) { 
        $url = env('APP_URL_WEB_PAGE');
        return redirect($url.'/#/user/create-password?token='.$token.'&email='.$email)->with(['token' => $token]);
    }
}
