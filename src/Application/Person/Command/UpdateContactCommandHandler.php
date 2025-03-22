<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Person\Exception\ContactNotFoundException;
use App\Domain\Person\Repository\ContactRepositoryInterface;
use App\Domain\Person\Service\ContactManagerInterface;
use Symfony\Component\Uid\Uuid;

class UpdateContactCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ContactRepositoryInterface $repository,
        private readonly ContactManagerInterface $contactManager
    ) {
    }

    public function __invoke(UpdateContactCommand $command)
    {
        $contact = $this->repository->search(Uuid::fromString($command->id));

        if (null === $contact) {
            throw new ContactNotFoundException();
        }

        $content = $this->contactManager->prepareContactContent(
            $contact->getContactType(),
            $command->content
        )?->jsonSerialize();

        $contact->setContent($content);

        $this->repository->save($contact);
    }
}
