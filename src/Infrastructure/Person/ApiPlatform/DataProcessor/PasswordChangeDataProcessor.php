<?php

namespace App\Infrastructure\Person\ApiPlatform\DataProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Person\Command\PasswordChangeCommand;
use App\Application\Shared\Command\CommandBusInterface;
use App\Infrastructure\Person\ApiPlatform\Payload\PasswordChangePayload;
use App\Infrastructure\Person\Auth;
use Symfony\Component\Security\Core\Security;

class PasswordChangeDataProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly Security $security
    ) {
    }

    /**
     * @param mixed|PasswordChangePayload $data
     *
     * @return void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /** @var Auth $auth */
        $auth = $this->security->getUser();

        $this->commandBus->dispatch(new PasswordChangeCommand(
            $auth?->getPersonId(),
            $data->oldPassword,
            $data->newPassword
        ));
    }
}
