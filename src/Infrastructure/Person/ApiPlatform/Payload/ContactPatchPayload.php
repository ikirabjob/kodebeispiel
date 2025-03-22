<?php

namespace App\Infrastructure\Person\ApiPlatform\Payload;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class ContactPatchPayload
{
    public function __construct(
        #[Assert\NotBlank]
        #[Groups(['patch'])]
        public ?array $content
    ) {
    }
}
