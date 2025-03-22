<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Person\Enums\ContactTypeEnum;
use App\Domain\Person\Exception\PersonNotFoundException;
use App\Domain\Person\Model\Person;
use App\Domain\Person\Repository\PersonRepositoryInterface;
use App\Domain\Person\Service\ContactManagerInterface;
use App\Domain\Person\ValueObject\Contact\ContactMetadata;
use Symfony\Component\Uid\Uuid;

class CreateContactCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly PersonRepositoryInterface $personRepository,
        private readonly ContactManagerInterface $contactManager
    ) {
    }

    public function __invoke(CreateContactCommand $command)
    {
        /** @var Person|null $person */
        $person = $this->personRepository->search(Uuid::fromString($command->personId));

        if (null === $person) {
            throw new PersonNotFoundException();
        }

        $isPrimary = false;

        $personContact = $this->contactManager
            ->getPersonContactsByType($person, ContactTypeEnum::from($command->contactType));

        if (empty($personContact)) {
            $isPrimary = true;
        }

        $this->contactManager->persistContact(
            $person,
            ContactTypeEnum::from($command->contactType),
            $this->contactManager->prepareContactContent($command->contactType, $command->content),
            new ContactMetadata(
                true,
                $isPrimary,
                true
            )
        );
    }
}
