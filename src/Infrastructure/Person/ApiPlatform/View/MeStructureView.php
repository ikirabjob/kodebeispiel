<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

final class MeStructureView
{
    public function __construct(
        #[Groups(['item'])]
        public readonly ?int $level,
        #[Groups(['item'])]
        public readonly ?float $rate,
        #[Groups(['item'])]
        public readonly ?float $personalPoints,
        #[Groups(['item'])]
        public readonly ?float $structurePoints,
    ) {
    }
}
