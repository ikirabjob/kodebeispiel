<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

final class InviteView
{
    public function __construct(
        #[Groups(['item', 'list'])]
        public ?string $id,
        #[Groups(['item', 'list'])]
        public ?string $personId,
        #[Groups(['item', 'list'])]
        public ?int $status,
        #[Groups(['item', 'list'])]
        public ?int $channel,
        #[Groups(['item', 'list'])]
        public ?string $destination
    ) {
    }
}
