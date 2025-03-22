<?php

namespace App\Domain\Person\Event;

use App\Domain\Shared\Event\DomainEventInterface;
use Symfony\Component\Uid\Uuid;

class LoginNameChanged implements DomainEventInterface
{
    public function __construct(
        public readonly Uuid $loginId,
        public readonly string $newLoginName
    ) {
    }
}
