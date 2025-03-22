<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

final class BlockedPersonView
{
    public function __construct(
        #[Groups(['item', 'list'])]
        public ?string $id,
        #[Groups(['item', 'list'])]
        public ?string $firstName,
        #[Groups(['item', 'list'])]
        public ?string $lastName,
        #[Groups(['item', 'list'])]
        public ?string $middleName,
        #[Groups(['item', 'list'])]
        public ?int $status,
        #[Groups(['item'])]
        public ?int $onboardingStep,
        #[Groups(['item'])]
        public ?string $inviteId,
        #[Groups(['item'])]
        public ?int $personType,
        #[Groups(['item', 'list'])]
        public ?int $inviteFromAdmin,
        #[Groups(['item', 'list'])]
        public ?int $legacyId = null,
        #[Groups(['item', 'list'])]
        public ?int $reviseStatus = null,
    ) {
    }
}
