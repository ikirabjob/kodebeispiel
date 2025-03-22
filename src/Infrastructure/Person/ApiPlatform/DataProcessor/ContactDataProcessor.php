<?php

namespace App\Infrastructure\Person\ApiPlatform\DataProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Person\Command\CreateContactCommand;
use App\Application\Person\Command\UpdateContactCommand;
use App\Application\Shared\Command\CommandBusInterface;
use App\Infrastructure\Person\ApiPlatform\Payload\ContactPayload;

class ContactDataProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
    }

    /**
     * @param ContactPayload $data
     *
     * @return void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($operation instanceof Patch) {
            $contactId = $uriVariables['id'] ?? null;
            $this->commandBus->dispatch(new UpdateContactCommand(
                $contactId,
                array_shift($data->content)
            ));
        }

        if ($operation instanceof Post) {
            $this->commandBus->dispatch(new CreateContactCommand(
                $data->personId,
                $data->contactType,
                array_shift($data->content)
            ));
        }
    }
}
