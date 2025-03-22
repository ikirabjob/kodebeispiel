<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

final class ContactView
{
    public function __construct(
        #[Groups(['item', 'list', 'person_contact'])]
        public ?string $id,
        #[Groups(['item', 'list', 'person_contact'])]
        public ?string $personId,
        #[Groups(['item', 'list', 'person_contact'])]
        public ?int $contactType,
        #[Groups(['item', 'person_contact'])]
        public ?array $content,
        #[Groups(['item', 'list', 'person_contact'])]
        public ?bool $isVerified,
        #[Groups(['item', 'list', 'person_contact'])]
        public ?bool $isActive,
        #[Groups(['item', 'list', 'person_contact'])]
        public ?bool $isPrimary,
    ) {
    }
}
