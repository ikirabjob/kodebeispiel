<?php

namespace App\Infrastructure\Person\ApiPlatform\Payload;

use Symfony\Component\Serializer\Annotation\Groups;

final class PersonPayload
{
    public function __construct(
        #[Groups(['create', 'update'])]
        public ?string $firstName,
        #[Groups(['create', 'update'])]
        public ?string $lastName,
        #[Groups(['create', 'update'])]
        public ?string $middleName,
    ) {
    }
}
