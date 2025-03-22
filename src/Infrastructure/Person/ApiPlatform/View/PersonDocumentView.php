<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

class PersonDocumentView
{
    public function __construct(
        #[Groups(['item', 'list'])]
        public ?string $id,
        #[Groups(['item', 'list'])]
        public ?string $documentType,
        #[Groups(['item', 'list'])]
        public ?string $documentPath,
        #[Groups(['item', 'list'])]
        public ?string $personId,
        #[Groups(['item', 'list'])]
        public ?int $status,
        #[Groups(['item', 'list'])]
        public ?array $documentData,
        #[Groups(['item', 'list'])]
        public ?bool $isSigned,
        #[Groups(['item', 'list'])]
        public \DateTimeInterface $createdAt,
    ) {
    }
}
