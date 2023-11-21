<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedException extends HttpException
{
    public static function notLoggedIn(): self
    {
        return new static(401, __('jwt.not_logged_in'), null, []);
    }

    public static function tokenIsExpired(): self
    {
        return new static(401, __('jwt.expired'), null, []);
    }

    public static function tokenIsInvalid(): self
    {
        return new static(401, __('jwt.invalid'), null, []);
    }
}
