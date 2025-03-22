<?php

namespace App\Infrastructure\Person\ApiPlatform\Payload;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class PasswordChangePayload
{
    public function __construct(
        #[Assert\NotBlank, Groups(['password_change'])]
        public ?string $oldPassword,
        #[Assert\NotBlank, Groups(['password_change'])]
        public ?string $newPassword
    ) {
    }
}
