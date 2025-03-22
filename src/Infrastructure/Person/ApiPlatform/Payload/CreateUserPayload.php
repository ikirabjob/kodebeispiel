<?php

namespace App\Infrastructure\Person\ApiPlatform\Payload;

use ApiPlatform\Metadata\Post;
use App\Infrastructure\Person\ApiPlatform\DataProcessor\CreateUserDataProcessor;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[Post(
    uriTemplate: 'people/create-user-from-admin',
    shortName: 'Person',
    denormalizationContext: ['groups' => ['create_user_from_admin']],
    security: 'is_granted("ROLE_ADMINISTRATOR")',
    output: false,
    processor: CreateUserDataProcessor::class
)]
class CreateUserPayload
{
    public function __construct(
        #[Assert\Email, Assert\NotBlank, Groups(['create_user_from_admin'])]
        public ?string $email,
    ) {
    }
}
