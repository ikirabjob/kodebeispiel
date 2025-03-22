<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Person\Exception\PersonNotFoundException;
use App\Domain\Person\Model\Person;
use App\Domain\Person\Repository\PersonRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class UpdatePersonCommandHandler implements CommandHandlerInterface
{
    public function __construct(private readonly PersonRepositoryInterface $repository)
    {
    }

    /**
     * @throws PersonNotFoundException
     */
    public function __invoke(UpdatePersonCommand $command): Person
    {
        $person = $this->repository->search(Uuid::fromString($command->id));

        if (null === $person) {
            throw new PersonNotFoundException();
        }

        $person->getUserName()->changeFirstName($command->firstName);
        $person->getUserName()->changeLastName($command->lastName);
        $person->getUserName()->changeMiddleName($command->middleName);

        $this->repository->save($person);

        return $person;
    }
}
