<?php

namespace App\Domain\Person\Service;

interface PasswordGeneratorServiceInterface
{
    public function generatePassword(int $length): string;
}
