<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

final class MeView
{
    public function __construct(
        #[Groups(['item'])]
        public readonly Uuid $loginId,
        #[Groups(['item'])]
        public readonly Uuid $personId,
        #[Groups(['item'])]
        public readonly int $status,
        #[Groups(['item'])]
        public readonly string $userIdentifier,
        #[Groups(['item'])]
        public readonly array $balances,
        #[Groups(['item'])]
        public readonly int $personType,
        #[Groups(['item'])]
        public readonly MeStructureView $structure,
        #[Groups(['item'])]
        public readonly PersonView $person,
        #[Groups(['item'])]
        public readonly ?Uuid $walletId = null,
    ) {
    }
}
