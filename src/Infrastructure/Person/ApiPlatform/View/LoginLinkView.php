<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

class LoginLinkView
{
    public function __construct(
        #[Groups(['item', 'list'])]
        public ?string $token
    ) {
    }
}
