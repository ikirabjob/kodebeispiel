<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

final class PersonValidatedView
{
    public function __construct(
        #[Groups(['item'])]
        public ?bool $isValidated
    ) {
    }
}
