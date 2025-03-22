<?php

namespace App\Infrastructure\Person\ApiPlatform\Payload;

use Symfony\Component\Serializer\Annotation\Groups;

final class PersonProfilePayload
{
    public function __construct(
        #[Groups(['create', 'update'])]
        public ?string $mailingLang,
        #[Groups(['create', 'update'])]
        public ?int $newsLetter,
        #[Groups(['create', 'update'])]
        public ?int $eventLetter,
        #[Groups(['create', 'update'])]
        public ?int $confirmationCode,
        #[Groups(['create', 'update'])]
        public ?int $securityCodes,
        #[Groups(['create', 'update'])]
        public ?int $hideBalance,
        #[Groups(['create', 'update'])]
        public ?int $hideContract,
        #[Groups(['create', 'update'])]
        public ?int $hideTransaction,
    ) {
    }
}
