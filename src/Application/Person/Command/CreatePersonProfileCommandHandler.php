<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Person\Exception\PersonNotFoundException;
use App\Domain\Person\Model\Profile;
use App\Domain\Person\Repository\PersonRepositoryInterface;
use App\Domain\Person\Repository\ProfileRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class CreatePersonProfileCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly PersonRepositoryInterface $repository,
        private readonly ProfileRepositoryInterface $profileRepository
    ) {
    }

    public function __invoke(CreatePersonProfileCommand $command)
    {
        $person = $this->repository->search(Uuid::fromString($command->personId));

        if (null === $person) {
            throw new PersonNotFoundException();
        }

        $profile = Profile::create($person);

        $this->profileRepository->save($profile);
    }
}
