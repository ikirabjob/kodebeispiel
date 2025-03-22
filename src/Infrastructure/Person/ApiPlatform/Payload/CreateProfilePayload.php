<?php

namespace App\Infrastructure\Person\ApiPlatform\Payload;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateProfilePayload
{
    public function __construct(
        #[Assert\NotBlank]
        #[Groups(['create'])]
        public ?string $personId,
    ) {
    }
}
