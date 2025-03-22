<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Person\Exception\PersonNotFoundException;
use App\Domain\Person\Repository\PersonRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class DeletePersonCommandHandler implements CommandHandlerInterface
{
    public function __construct(private readonly PersonRepositoryInterface $repository)
    {
    }

    /**
     * @throws PersonNotFoundException
     */
    public function __invoke(DeletePersonCommand $command): void
    {
        $this->repository->delete(Uuid::fromString($command->id));
    }
}
