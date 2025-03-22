<?php

namespace App\Infrastructure\Shared\Messenger;

use App\Application\Shared\Event\EventBusInterface;
use App\Domain\Shared\Event\DomainEventInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerEventBus implements EventBusInterface
{
    // use HandleTrait;
    use MessageBusExceptionTrait;

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->messageBus = $eventBus;
    }

    /**
     * @throws \Throwable
     */
    public function dispatch(DomainEventInterface $event): mixed
    {
        try {
            return $this->messageBus->dispatch($event);
        } catch (HandlerFailedException $e) {
            $this->throwException($e);
        }
    }
}
