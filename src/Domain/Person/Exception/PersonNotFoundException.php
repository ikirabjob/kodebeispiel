<?php

namespace App\Domain\Person\Exception;

use App\Domain\Shared\Exception\NotFoundException;
use Symfony\Component\Uid\Uuid;

class PersonNotFoundException extends NotFoundException
{
    public static function createWithPersonId(Uuid $personId): self
    {
        return new self(sprintf("Person with id '%s' not found", $personId->toRfc4122()));
    }
}
