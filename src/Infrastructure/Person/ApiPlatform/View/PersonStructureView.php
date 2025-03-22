<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

final class PersonStructureView
{
    public function __construct(
        #[Groups(['list'])]
        public ?string $id,
        #[Groups(['list'])]
        public ?string $firstName,
        #[Groups(['list'])]
        public ?string $lastName,
        #[Groups(['list'])]
        public ?float $personalPoints,
        #[Groups(['list'])]
        public ?float $structurePoints,
        #[Groups(['list'])]
        public ?int $level,
        #[Groups(['list'])]
        public ?int $legacy_id,
    ) {
    }
}
