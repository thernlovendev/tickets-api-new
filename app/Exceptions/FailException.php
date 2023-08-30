<?php

namespace App\Exceptions;

use Exception;

class FailException extends Exception
{
    protected $errors;
    protected $message;


    public function __construct($errors, $message)
    {
        $this->errors = $errors;
        $this->message = $message;

    }

    public function render($request = null)
    {
        return response()->json(['message' => $this->message, 'errors' => $this->errors], 422);
    }

}