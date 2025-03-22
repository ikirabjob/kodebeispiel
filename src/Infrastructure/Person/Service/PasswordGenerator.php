<?php

namespace App\Infrastructure\Person\Service;

use App\Domain\Person\Service\PasswordGeneratorServiceInterface;

final class PasswordGenerator implements PasswordGeneratorServiceInterface
{
    /**
     * @throws \Exception
     */
    public function generatePassword(int $length): string
    {
        return substr(sha1(random_bytes(20)), 0, $length);
    }
}
