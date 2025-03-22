<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Event\EventBusInterface;
use App\Domain\Person\Event\LoginPasswordChanged;
use App\Domain\Person\Repository\LoginRepositoryInterface;
use App\Domain\Person\Service\PasswordGeneratorServiceInterface;
use App\Domain\Person\ValueObject\Auth\HashedPassword;
use Doctrine\ORM\NonUniqueResultException;

class PasswordResetCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly LoginRepositoryInterface $loginRepository,
        private readonly EventBusInterface $eventBus,
        private readonly PasswordGeneratorServiceInterface $passwordGeneratorService,
    ) {
    }

    /**
     * @throws NonUniqueResultException
     */
    public function __invoke(PasswordResetCommand $command)
    {
        $login = $this->loginRepository->getByLoginName($command->loginName);
        $newPlainPassword = $this->passwordGeneratorService->generatePassword(10);
        $newHashedPassword = HashedPassword::encode($newPlainPassword);
        $login->changePassword($newHashedPassword);
        $this->loginRepository->save($login);

        $this->eventBus->dispatch(new LoginPasswordChanged(
            $login->getLoginId(),
            $newPlainPassword
        ));
    }
}
