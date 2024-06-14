<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler;

class CustomExceptionHandler extends Handler
{
    public function render($request, \Throwable $exception)
    {
        return response()->json($exception->getMessage(), $exception->getCode());
    }
}
