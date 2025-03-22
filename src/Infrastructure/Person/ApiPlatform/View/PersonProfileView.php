<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

final class PersonProfileView
{
    public function __construct(
        #[Groups(['item', 'list'])]
        public ?string $id,
        #[Groups(['item', 'list'])]
        public ?string $personId,
        #[Groups(['item', 'list'])]
        public ?string $mailingLang,
        #[Groups(['item', 'list'])]
        public ?int $newsLetter,
        #[Groups(['item', 'list'])]
        public ?int $eventLetter,
        #[Groups(['item', 'list'])]
        public ?int $confirmationCode,
        #[Groups(['item', 'list'])]
        public ?int $securityCodes,
        #[Groups(['item', 'list'])]
        public ?int $hideBalance,
        #[Groups(['item', 'list'])]
        public ?int $hideContract,
        #[Groups(['item', 'list'])]
        public ?int $hideTransaction,
    ) {
    }
}
