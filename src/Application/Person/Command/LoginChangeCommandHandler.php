<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Event\EventBusInterface;
use App\Domain\Person\Event\LoginNameChanged;
use App\Domain\Person\Repository\LoginRepositoryInterface;
use App\Domain\Person\ValueObject\Auth\UserIdentifier;
use Symfony\Component\Uid\UuidV4;

final class LoginChangeCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly LoginRepositoryInterface $loginRepository,
        private readonly EventBusInterface $eventBus
    ) {
    }

    public function __invoke(LoginChangeCommand $command): void
    {
        $login = $this->loginRepository->searchByPersonId(UuidV4::fromString($command->personId));
        $login->changeLogin(UserIdentifier::create($command->loginName, $command->loginType));
        $this->loginRepository->save($login);

        $this->eventBus->dispatch(new LoginNameChanged(
            $login->getLoginId(),
            $command->loginName
        ));
    }
}
