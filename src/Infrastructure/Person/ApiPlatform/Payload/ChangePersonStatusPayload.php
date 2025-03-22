<?php

namespace App\Infrastructure\Person\ApiPlatform\Payload;

use ApiPlatform\Metadata\Post;
use App\Infrastructure\Person\ApiPlatform\DataProcessor\ChangePersonStatusDataProcessor;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[Post(
    uriTemplate: 'people/change-person-status',
    openapiContext: ['summary' => 'Change person status'],
    shortName: 'Person',
    denormalizationContext: ['groups' => ['change_person_status']],
    security: 'is_granted("ROLE_ADMINISTRATOR")',
    processor: ChangePersonStatusDataProcessor::class
)]
class ChangePersonStatusPayload
{
    public function __construct(
        #[Assert\NotBlank, Assert\Uuid]
        #[Groups(['change_person_status'])]
        public string $personId,
        #[Assert\NotBlank]
        #[Groups(['change_person_status'])]
        public int $status
    ) {
    }
}
