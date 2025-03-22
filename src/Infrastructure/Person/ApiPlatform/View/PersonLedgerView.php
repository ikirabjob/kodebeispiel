<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

class PersonLedgerView
{
    public function __construct(
        #[Groups(['item', 'list'])]
        public float $amount,
        #[Groups(['item', 'list'])]
        public string $currency,
        #[Groups(['item', 'list'])]
        public int $operationType,
        #[Groups(['item', 'list'])]
        public \DateTimeInterface $createdAt,
        #[Groups(['item', 'list'])]
        public ?string $description,
        #[Groups(['item', 'list'])]
        public ?float $balance
    ) {
    }
}
