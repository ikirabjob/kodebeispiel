<?php

namespace App\Infrastructure\Person\ApiPlatform\Payload;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class PasswordChangeFromAdminPayload
{
    public function __construct(
        #[Assert\Uuid, Assert\NotBlank, Groups(['password_change'])]
        public ?string $personId,
        #[Assert\NotBlank, Groups(['password_change'])]
        public ?string $newPassword
    ) {
    }
}
