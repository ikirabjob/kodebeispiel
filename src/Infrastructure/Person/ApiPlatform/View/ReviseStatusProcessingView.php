<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

class ReviseStatusProcessingView
{
    public function __construct(
        #[Groups(['item', 'list'])]
        public Uuid $id,
        #[Groups(['item', 'list'])]
        public Uuid $personId,
        #[Groups(['item', 'list'])]
        public int $status,
        #[Groups(['item', 'list'])]
        public \DateTimeInterface $createdAt,
    ) {
    }
}
