<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

final class PersonContractView
{
    public function __construct(
        #[Groups(['item', 'list'])]
        public string $id,
        #[Groups(['item', 'list'])]
        public string $personId,
        #[Groups(['item', 'list'])]
        public string $contractTypeId,
        #[Groups(['item', 'list'])]
        public string $currencyId,
        #[Groups(['item', 'list'])]
        public string $contractNumber,
        #[Groups(['item', 'list'])]
        public float $contractAmount,
        #[Groups(['item', 'list'])]
        public int $contractStatus
    ) {
    }

    public function getPersonId(): string
    {
        return $this->personId;
    }
}
