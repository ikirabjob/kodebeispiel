<?php

namespace App\Infrastructure\Shared\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

class EmailGeneratorView
{
    public function __construct(
        #[Groups(['item', 'list'])]
        public ?string $email
    ) {
    }
}
