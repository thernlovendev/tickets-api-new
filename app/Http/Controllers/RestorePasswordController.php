<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestorePasswordController extends Controller
{
    public function showResetPasswordForm($token) { 
        // $url = env('APP_URL_WEB_PAGE');
        // return redirect($url.'#/user/create-password')->with(['token' => $token]);
        return view('forgetPasswordLink', ['token' => $token]);
    }
}
