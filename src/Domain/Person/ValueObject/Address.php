<?php

namespace App\Domain\Person\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;
use Symfony\Component\Uid\Uuid;

#[Embeddable]
class Address
{
    public function __construct(
        #[ORM\Column(type: 'uuid', nullable: true)]
        private Uuid $countryId,
        #[ORM\Column(name: 'postal_code', type: 'string', length: 255, nullable: true)]
        private string $postalCode,
        #[ORM\Column(name: 'city', type: 'string', length: 255, nullable: true)]
        private string $city,
        #[ORM\Column(name: 'street', type: 'string', length: 255, nullable: true)]
        private string $street,
        #[ORM\Column(name: 'house', type: 'string', length: 255, nullable: true)]
        private string $house
    ) {
    }

    public function getCountryId(): Uuid
    {
        return $this->countryId;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getHouse(): string
    {
        return $this->house;
    }

    public function setCountryId(Uuid $countryId): void
    {
        $this->countryId = $countryId;
    }

    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function setHouse(string $house): void
    {
        $this->house = $house;
    }
}
