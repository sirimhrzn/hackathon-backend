<?php

namespace App\Exceptions;

use App\Enums\ExceptionEnum;
use App\Helpers\ResponseBuilder;
use App\ReadOnlyClasses\ROResponse;
use Exception;
use Illuminate\Http\Request;

class CustomException extends Exception
{
    public function __construct(
        private ExceptionEnum $exception
    ) {
    }

    public function render(Request $request)
    {
        $response = $this->handle_exception();
        return response()->json($response->message, $response->status_code);
    }

    public function handle_exception(): ROResponse
    {
        $response =  match ($this->exception) {
            ExceptionEnum::Unimplemented => ["feature unimplemented",500],
            default => ["something went wrong",500]
        };
        return ResponseBuilder::buildResponse($response[0], $response[1]);
    }
}
