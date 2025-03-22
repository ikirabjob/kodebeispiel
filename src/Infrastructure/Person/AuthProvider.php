<?php

namespace App\Infrastructure\Person;

use App\Domain\Person\Enums\PersonStatusEnum;
use App\Domain\Person\Exception\PersonBlockedException;
use App\Domain\Person\Model\Person;
use App\Domain\Person\Repository\LoginRepositoryInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AuthProvider implements UserProviderInterface
{
    private LoginRepositoryInterface $loginRepository;

    public function __construct(LoginRepositoryInterface $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return Auth::class === $class;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $login = $this->loginRepository->getByLoginName($identifier);

        if (!$this->validateLoginUser($login->getPerson())) {
            throw new PersonBlockedException('User is blocked');
        }

        return Auth::create(...$this->loginRepository->getCredentialsByLoginName($identifier));
    }

    public function validateLoginUser(Person $person): bool
    {
        return $person->getStatus() !== PersonStatusEnum::BLOCKED->value;
    }
}
