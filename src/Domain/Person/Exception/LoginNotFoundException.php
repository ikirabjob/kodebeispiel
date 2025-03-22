<?php

namespace App\Domain\Person\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class LoginNotFoundException extends HttpException
{
    public function __construct(string $message = '', \Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct(404, $message, $previous, $headers, $code);
    }
}
