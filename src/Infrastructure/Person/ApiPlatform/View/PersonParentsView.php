<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

class PersonParentsView
{
    public function __construct(
        #[Groups(['item', 'list'])]
        public string $personId,
        #[Groups(['item', 'list'])]
        public string $firstName,
        #[Groups(['item', 'list'])]
        public string $lastName,
        #[Groups(['item', 'list'])]
        public ?int $legacyId,
        #[Groups(['item', 'list'])]
        public ?string $parentPersonId,
    ) {
    }
}
