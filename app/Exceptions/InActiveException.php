<?php

namespace App\Exceptions;

use Exception;

class InActiveException extends Exception
{
    public function render($request)
    {
        \Auth::logout();
        return view('errors.in_active');
    }

}
