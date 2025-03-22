<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Person\Enums\ContactTypeEnum;
use App\Domain\Person\Enums\LoginTypeEnum;
use App\Domain\Person\Enums\PersonTypeEnum;
use App\Domain\Person\Exception\PersonNotFoundException;
use App\Domain\Person\Model\Login;
use App\Domain\Person\Repository\LoginRepositoryInterface;
use App\Domain\Person\Repository\PersonRepositoryInterface;
use App\Domain\Person\Service\ContactManagerInterface;
use App\Domain\Person\Service\PasswordGeneratorServiceInterface;
use App\Domain\Person\Specification\Checker\LoginNameUniquenessCheckerInterface;
use App\Domain\Person\Specification\Rule\LoginNameMustBeUniqueRule;
use App\Domain\Person\ValueObject\Auth\Credentials;
use App\Domain\Person\ValueObject\Auth\HashedPassword;
use App\Domain\Person\ValueObject\Auth\UserIdentifier;
use App\Domain\Shared\Exception\BusinessRuleValidationException;
use App\Domain\Shared\Specification\BusinessRuleCheckerTrait;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

final class CreateUserCommandHandler implements CommandHandlerInterface
{
    use BusinessRuleCheckerTrait;

    public function __construct(
        private readonly LoginRepositoryInterface $loginRepository,
        private readonly PersonRepositoryInterface $personRepository,
        private readonly LoginNameUniquenessCheckerInterface $loginNameUniquenessChecker,
        private readonly PasswordGeneratorServiceInterface $passwordGeneratorService,
        private readonly ContactManagerInterface $contactManager,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    /**
     * @throws BusinessRuleValidationException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $userIdentifier = UserIdentifier::create(
            $command->email,
            LoginTypeEnum::LOGIN_TYPE_EMAIL->value
        );

        self::checkRule(new LoginNameMustBeUniqueRule($this->loginNameUniquenessChecker, $userIdentifier));

        $plainPassword = $this->passwordGeneratorService->generatePassword(10);
        $inviterPerson = $this->personRepository->search(Uuid::fromString($command->parentPersonId));

        if (null === $inviterPerson) {
            throw new PersonNotFoundException(sprintf('Person with id %s not found', $command->parentPersonId));
        }

        $login = Login::create(
            new UuidV4(),
            new Credentials(
                $userIdentifier,
                HashedPassword::encode($plainPassword)
            ),
            $inviterPerson,
            PersonTypeEnum::OLD->value
        );

        $this->loginRepository->save($login);

        $content = [
            [
                'email' => $command->email,
            ],
        ];

        $this->commandBus->dispatch(new CreateContactCommand(
            $login->getPerson()->getPersonId()->toRfc4122(),
            ContactTypeEnum::PERSONAL_EMAIL->value,
            array_shift($content)
        ));
    }
}
