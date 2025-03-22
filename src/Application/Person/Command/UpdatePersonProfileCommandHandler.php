<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Person\Exception\ProfileNotFoundException;
use App\Domain\Person\Repository\ProfileRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class UpdatePersonProfileCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProfileRepositoryInterface $repository
    ) {
    }

    public function __invoke(UpdatePersonProfileCommand $command)
    {
        $profile = $this->repository->search(Uuid::fromString($command->id));

        if (null === $profile) {
            throw new ProfileNotFoundException();
        }

        $profile->setMailingLang($command->mailingLang);
        $profile->setNewsLetter($command->newsLetter);
        $profile->setEventLetter($command->eventLetter);
        $profile->setConfirmationCode($command->confirmationCode);
        $profile->setSecurityCodes($command->securityCodes);
        $profile->setHideBalance($command->hideBalance);
        $profile->setHideContract($command->hideContract);
        $profile->setHideTransaction($command->hideTransaction);

        $this->repository->save($profile);
    }
}
