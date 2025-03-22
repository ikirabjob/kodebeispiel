<?php

namespace App\Infrastructure\Person\ApiPlatform\DataProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Person\Command\UpdatePersonProfileCommand;
use App\Application\Shared\Command\CommandBusInterface;
use App\Infrastructure\Person\ApiPlatform\Payload\PersonProfilePayload;

class ProfileDataProcessor implements ProcessorInterface
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    /**
     * @param PersonProfilePayload $data
     *
     * @return void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($operation instanceof Patch) {
            if (null !== $profileId = ($uriVariables['id'] ?? null)) {
                $this->commandBus->dispatch(new UpdatePersonProfileCommand(
                    $profileId,
                    $data->mailingLang,
                    $data->newsLetter,
                    $data->eventLetter,
                    $data->confirmationCode,
                    $data->securityCodes,
                    $data->hideBalance,
                    $data->hideContract,
                    $data->hideTransaction
                ));
            }
        }
    }
}
