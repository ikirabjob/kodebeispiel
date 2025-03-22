<?php

namespace App\Application\Person\Event;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Event\EventHandlerInterface;
use App\Domain\Person\Event\LoginNameChanged;
use Psr\Log\LoggerInterface;

class LoginNameChangedHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public function __invoke(LoginNameChanged $event): void
    {
        // TODO Person notification with default communication channel
        $this->logger->debug($event->loginId);
        $this->logger->debug($event->newLoginName);
    }
}
