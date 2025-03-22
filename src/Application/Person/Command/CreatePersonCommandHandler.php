<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Person\Enums\PersonStatusEnum;
use App\Domain\Person\Model\Person;
use App\Domain\Person\Model\Profile;
use App\Domain\Person\Repository\PersonRepositoryInterface;
use App\Domain\Person\Repository\ProfileRepositoryInterface;
use App\Domain\Person\ValueObject\UserName;
use Symfony\Component\Uid\Uuid;

final class CreatePersonCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly PersonRepositoryInterface $repository,
        private readonly ProfileRepositoryInterface $profileRepository
    ) {
    }

    public function __invoke(CreatePersonCommand $command): Person
    {
        $person = Person::create(
            $this->repository->search(Uuid::fromString($command->parentPersonId)),
            PersonStatusEnum::STATUS_CANDIDATE_NO_AGREEMENT->value,
            new UserName($command->firstName, $command->lastName, $command->middleName)
        );

        $profile = Profile::create($person);

        $this->repository->save($person);
        $this->profileRepository->save($profile);

        return $person;
    }
}
