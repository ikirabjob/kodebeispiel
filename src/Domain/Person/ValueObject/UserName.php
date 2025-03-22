<?php

namespace App\Domain\Person\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class UserName
{
    public function __construct(
        #[ORM\Column(name: 'first_name', type: 'string', length: 255, nullable: true)]
        private ?string $firstName,
        #[ORM\Column(name: 'last_name', type: 'string', length: 255, nullable: true)]
        private ?string $lastName,
        #[ORM\Column(name: 'middle_name', type: 'string', length: 255, nullable: true)]
        private ?string $middleName,
    ) {
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function changeFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function changeLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function changeMiddleName(?string $middleName): void
    {
        $this->middleName = $middleName;
    }

    public function getFullName(): string
    {
        return implode(' ', array_filter([
            $this->getFirstName(),
            $this->getLastName(),
        ], fn (string $data) => !empty(trim($data))));
    }
}
