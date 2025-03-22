<?php

namespace App\Infrastructure\Shared\Doctrine\Types;

use App\Domain\Person\ValueObject\Profile;

class ProfileType extends ObjectJsonType
{
    public const TYPE_NAME = 'profile';
    protected ?string $className = Profile::class;
}
