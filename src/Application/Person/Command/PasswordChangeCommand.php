<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandInterface;
use Symfony\Component\Uid\Uuid;

class PasswordChangeCommand implements CommandInterface
{
    public function __construct(
        public Uuid $personId,
        public string $oldPassword,
        public string $newPassword,
    ) {
    }
}
