<?php

namespace App\Helpers;

use App\ReadOnlyClasses\ROResponse;

final readonly class ResponseBuilder
{
    public static function buildResponse(array|string $message, int $status_code, string|null $extra_message = null): ROResponse
    {
        if(!is_array($message)) {
            $data = ['message' => $message];
        } else {
            if(is_null($extra_message)) {
                return $message;
            }
            $message['message'] = $extra_message;
            $data = $message;
        }
        return new ROResponse($data, $status_code);
    }
}
