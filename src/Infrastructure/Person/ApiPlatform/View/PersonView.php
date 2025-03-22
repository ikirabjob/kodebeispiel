<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

final class PersonView
{
    public function __construct(
        #[Groups(['item', 'list'])]
        public ?string $id,
        #[Groups(['item', 'list'])]
        public ?string $firstName,
        #[Groups(['item', 'list'])]
        public ?string $lastName,
        #[Groups(['item', 'list'])]
        public ?string $middleName,
        #[Groups(['item', 'list'])]
        public ?int $status,
        #[Groups(['item'])]
        public ?int $personType
    ) {
    }
}
