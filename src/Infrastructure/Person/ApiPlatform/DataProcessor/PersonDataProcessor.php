<?php

namespace App\Infrastructure\Person\ApiPlatform\DataProcessor;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Person\Command\CreatePersonCommand;
use App\Application\Person\Command\DeletePersonCommand;
use App\Application\Person\Command\UpdatePersonCommand;
use App\Application\Shared\Command\CommandBusInterface;
use App\Infrastructure\Person\ApiPlatform\Payload\PersonPayload;
use App\Infrastructure\Person\ApiPlatform\View\PersonView;
use Symfony\Component\Uid\UuidV4;

class PersonDataProcessor implements ProcessorInterface
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    /**
     * @param PersonView|PersonPayload $data
     *
     * @return void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($operation instanceof Delete) {
            $this->commandBus->dispatch(new DeletePersonCommand($data->id));
        } elseif ($operation instanceof Post) {
            $this->commandBus->dispatch(new CreatePersonCommand(
                new UuidV4(),
                $data->firstName,
                $data->lastName,
                $data->middleName,
            ));
        } elseif ($operation instanceof Put) {
            $this->commandBus->dispatch(new UpdatePersonCommand(
                $uriVariables['id'],
                $data->firstName,
                $data->lastName,
                $data->middleName,
            ));
        }
    }
}
