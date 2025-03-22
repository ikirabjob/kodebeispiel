<?php

namespace App\Infrastructure\Person;

use ApiPlatform\Validator\Exception\ValidationException;
use App\Domain\Person\Model\Person;
use App\Domain\Person\ValueObject\Auth\Credentials;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

class Auth implements UserInterface, PasswordAuthenticatedUserInterface
{
    private Uuid $uuid;

    private Person $person;

    private Credentials $credentials;

    private ?array $roles;

    private function __construct(
        Uuid $uuid,
        Person $person,
        Credentials $credentials,
        ?array $roles = []
    ) {
        $this->uuid = $uuid;
        $this->person = $person;
        $this->credentials = $credentials;
        $this->roles = $roles;
    }

    /**
     * @throws ValidationException
     */
    public static function create(
        Uuid $uuid,
        Person $person,
        Credentials $credentials,
        ?array $roles = []
    ): self {
        return new self(
            $uuid,
            $person,
            $credentials,
            $roles
        );
    }

    public function getRoles(): array
    {
        return $this->roles ?? [];
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->credentials->userIdentifier->toString();
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getPassword(): string
    {
        return $this->credentials->hashedPassword->toString();
    }

    public function getPersonId(): Uuid
    {
        return $this->person->getPersonId();
    }

    public function getPerson(): Person
    {
        return $this->person;
    }
}
