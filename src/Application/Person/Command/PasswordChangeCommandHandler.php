<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Event\EventBusInterface;
use App\Domain\Person\Event\LoginPasswordChanged;
use App\Domain\Person\Repository\LoginRepositoryInterface;
use App\Domain\Person\ValueObject\Auth\HashedPassword;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;

class PasswordChangeCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly LoginRepositoryInterface $loginRepository,
        private readonly EventBusInterface $eventBus
    ) {
    }

    public function __invoke(PasswordChangeCommand $command)
    {
        $login = $this->loginRepository->searchByPersonId($command->personId);

        if (HashedPassword::fromHash($login->getPassword())->match($command->oldPassword)) {
            $newHashedPassword = HashedPassword::encode($command->newPassword);
            $login->changePassword($newHashedPassword);
            $this->loginRepository->save($login);
        } else {
            throw new InvalidPasswordException();
        }

        $this->eventBus->dispatch(new LoginPasswordChanged(
            $login->getLoginId(),
            $command->newPassword
        ));
    }
}
