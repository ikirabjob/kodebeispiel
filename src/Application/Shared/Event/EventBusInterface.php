<?php

namespace App\Application\Shared\Event;

use App\Domain\Shared\Event\DomainEventInterface;

interface EventBusInterface
{
    public function dispatch(DomainEventInterface $event): mixed;
}
