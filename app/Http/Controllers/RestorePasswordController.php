<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestorePasswordController extends Controller
{
    public function showResetPasswordForm($token) { 
        return view('forgetPasswordLink', ['token' => $token]);
    }
}
