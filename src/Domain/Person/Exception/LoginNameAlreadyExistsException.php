<?php

namespace App\Domain\Person\Exception;

class LoginNameAlreadyExistsException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Login name already registered.');
    }
}
