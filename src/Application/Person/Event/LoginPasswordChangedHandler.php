<?php

namespace App\Application\Person\Event;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Event\EventHandlerInterface;
use App\Domain\Person\Event\LoginPasswordChanged;
use Psr\Log\LoggerInterface;

class LoginPasswordChangedHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public function __invoke(LoginPasswordChanged $event): void
    {
        // TODO Person notification with default communication channel
        $this->logger->debug($event->loginId);
        $this->logger->debug($event->newPlainPassword);
    }
}
