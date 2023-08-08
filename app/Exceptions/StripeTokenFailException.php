<?php

namespace App\Exceptions;

use Exception;

class StripeTokenFailException extends Exception
{
    protected $errors;

    public function __construct($errors)
    {
        $this->errors = $errors;
    }

    public function render($request)
    {
        return response()->json(['message' => 'The given data was invalid. Need to pay an additional amount', 'errors' => $this->errors], 422);
    }

}