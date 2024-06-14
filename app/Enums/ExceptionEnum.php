<?php

namespace App\Enums;

enum ExceptionEnum
{
    case InvalidMethod;
    case AuthenticationError;
    case InvalidTenant;
    case SomethingWentWrong;
    case InvalidPayload;
    case InvalidToken;
    case Unimplemented;
    case InvalidUser;
    case InvalidCredentials;
    case ResourceDoesNotExist;
    case InvalidQueryParam;
}
