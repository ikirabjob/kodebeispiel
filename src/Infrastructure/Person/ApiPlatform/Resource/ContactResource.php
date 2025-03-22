<?php

namespace App\Infrastructure\Person\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Domain\Person\Model\Contact;
use App\Infrastructure\Person\ApiPlatform\DataProcessor\ContactDataProcessor;
use App\Infrastructure\Person\ApiPlatform\DataProvider\ContactProvider;
use App\Infrastructure\Person\ApiPlatform\Payload\ContactPatchPayload;
use App\Infrastructure\Person\ApiPlatform\Payload\ContactPayload;
use App\Infrastructure\Person\ApiPlatform\View\ContactView;
use App\Infrastructure\Shared\ApiPlatform\Filter\SearchFilter;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Contacts',
    operations: [
        new Get(
            normalizationContext: ['groups' => ['item']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY")'
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['list']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY")'
        ),
        new Patch(
            normalizationContext: ['groups' => ['patch']],
            denormalizationContext: ['groups' => ['patch']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY")',
            input: ContactPatchPayload::class,
            output: false,
            processor: ContactDataProcessor::class,
        ),
        new Post(
            normalizationContext: ['groups' => ['create']],
            denormalizationContext: ['groups' => ['create']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY")',
            input: ContactPayload::class,
            output: false,
            processor: ContactDataProcessor::class,
        ),
    ],
    input: ContactPayload::class,
    output: ContactView::class,
    provider: ContactProvider::class
)]
#[ApiFilter(
    filterClass: SearchFilter::class,
    properties: [
        'personId' => 'exact',
    ]
)]
class ContactResource
{
    #[Assert\Uuid]
    private ?string $id;
    private ?string $personId;
    private ?int $contactType;
    private ?array $content;
    private ?bool $isVerified;
    private ?bool $isActive;
    private ?bool $isPrimary;

    public static function fromModel(Contact $model): self
    {
        $resource = new self();
        $resource->id = $model->getContactId()->toRfc4122();
        $resource->personId = $model->getPerson()->getPersonId()->toRfc4122();
        $resource->contactType = $model->getContactType();
        $resource->content = $model->getContent();
        $resource->isVerified = $model->isVerified();
        $resource->isActive = $model->isActive();
        $resource->isPrimary = $model->isPrimary();

        return $resource;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPersonId(): ?string
    {
        return $this->personId;
    }

    public function getContactType(): ?int
    {
        return $this->contactType;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function getIsPrimary(): ?bool
    {
        return $this->isPrimary;
    }
}
