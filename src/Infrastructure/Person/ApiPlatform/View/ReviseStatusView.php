<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

class ReviseStatusView
{
    public function __construct(
        #[Groups(['item', 'list'])]
        public ?int $reviseStatus
    ) {
    }
}
