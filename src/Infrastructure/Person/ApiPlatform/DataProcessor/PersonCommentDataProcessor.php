<?php

namespace App\Infrastructure\Person\ApiPlatform\DataProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Person\Command\UpdatePersonCommentCommand;
use App\Application\Shared\Command\CommandBusInterface;
use App\Infrastructure\Person\ApiPlatform\Payload\PersonPayload;
use App\Infrastructure\Person\ApiPlatform\View\PersonView;
use Symfony\Component\Uid\Uuid;

class PersonCommentDataProcessor implements ProcessorInterface
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
        if ($operation instanceof Post) {
            /** @var Uuid|null $personId */
            if (null !== $personId = ($uriVariables['id'] ?? null)) {
                $this->commandBus->dispatch(new UpdatePersonCommentCommand(
                    $personId->toRfc4122(),
                    $data->comment
                ));
            }
        }
    }
}
