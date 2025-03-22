<?php

namespace App\Infrastructure\Person\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Domain\Person\Model\Person;
use App\Domain\Person\Model\Profile;
use App\Infrastructure\Person\ApiPlatform\DataProcessor\PersonProfileDataProcessor;
use App\Infrastructure\Person\ApiPlatform\DataProcessor\ProfileDataProcessor;
use App\Infrastructure\Person\ApiPlatform\DataProvider\PersonProfileDataProvider;
use App\Infrastructure\Person\ApiPlatform\DataProvider\ProfileDataProvider;
use App\Infrastructure\Person\ApiPlatform\Payload\CreateProfilePayload;
use App\Infrastructure\Person\ApiPlatform\Payload\PersonProfilePayload;
use App\Infrastructure\Person\ApiPlatform\View\PersonProfileView;
use App\Infrastructure\Shared\ApiPlatform\Filter\SearchFilter;

#[ApiResource(
    shortName: 'Profile',
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
            uriTemplate: '/profile/{id}/update',
            uriVariables: [
                'id' => new Link(
                    toProperty: 'profileId',
                    fromClass: Profile::class
                ),
            ],
            normalizationContext: ['groups' => ['update']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY")',
            input: PersonProfilePayload::class,
            processor: ProfileDataProcessor::class
        ),
        new Get(
            uriTemplate: '/people/{id}/profile',
            uriVariables: [
                'id' => new Link(
                    toProperty: 'personId',
                    fromClass: Person::class
                ),
            ],
            openapiContext: ['summary' => 'Get Person profile resource'],
            shortName: 'Person',
            normalizationContext: ['groups' => ['item']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY")',
            provider: PersonProfileDataProvider::class
        ),
        new Post(
            normalizationContext: ['groups' => ['create']],
            denormalizationContext: ['groups' => ['create']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY")',
            input: CreateProfilePayload::class,
            processor: PersonProfileDataProcessor::class
        ),
    ],
    output: PersonProfileView::class,
    provider: ProfileDataProvider::class
)]
#[ApiFilter(
    filterClass: SearchFilter::class,
    properties: [
        'personId' => 'exact',
    ]
)]
class PersonProfileResource
{
    private ?string $id = null;
    private ?string $personId;
    private ?string $mailingLang;
    private ?int $newsLetter;
    private ?int $eventLetter;
    private ?int $confirmationCode;
    private ?int $securityCodes;
    private ?int $hideBalance;
    private ?int $hideContract;
    private ?int $hideTransaction;

    public static function fromModel(Profile $model): self
    {
        $resource = new self();
        $resource->id = $model->getProfileId()->toRfc4122();
        $resource->personId = $model->getPerson()->getPersonId()->toRfc4122();
        $resource->mailingLang = $model->getMailingLang();
        $resource->newsLetter = $model->getNewsLetter();
        $resource->eventLetter = $model->getEventLetter();
        $resource->confirmationCode = $model->getConfirmationCode();
        $resource->securityCodes = $model->getSecurityCodes();
        $resource->hideBalance = $model->getHideBalance();
        $resource->hideContract = $model->getHideContract();
        $resource->hideTransaction = $model->getHideTransaction();

        return $resource;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getPersonId(): ?string
    {
        return $this->personId;
    }

    public function setPersonId(?string $personId): void
    {
        $this->personId = $personId;
    }

    public function getMailingLang(): ?string
    {
        return $this->mailingLang;
    }

    public function setMailingLang(?string $mailingLang): void
    {
        $this->mailingLang = $mailingLang;
    }

    public function getNewsLetter(): ?int
    {
        return $this->newsLetter;
    }

    public function setNewsLetter(?int $newsLetter): void
    {
        $this->newsLetter = $newsLetter;
    }

    public function getEventLetter(): ?int
    {
        return $this->eventLetter;
    }

    public function setEventLetter(?int $eventLetter): void
    {
        $this->eventLetter = $eventLetter;
    }

    public function getConfirmationCode(): ?int
    {
        return $this->confirmationCode;
    }

    public function setConfirmationCode(?int $confirmationCode): void
    {
        $this->confirmationCode = $confirmationCode;
    }

    public function getSecurityCodes(): ?int
    {
        return $this->securityCodes;
    }

    public function setSecurityCodes(?int $securityCodes): void
    {
        $this->securityCodes = $securityCodes;
    }

    public function getHideBalance(): ?int
    {
        return $this->hideBalance;
    }

    public function setHideBalance(?int $hideBalance): void
    {
        $this->hideBalance = $hideBalance;
    }

    public function getHideContract(): ?int
    {
        return $this->hideContract;
    }

    public function setHideContract(?int $hideContract): void
    {
        $this->hideContract = $hideContract;
    }

    public function getHideTransaction(): ?int
    {
        return $this->hideTransaction;
    }

    public function setHideTransaction(?int $hideTransaction): void
    {
        $this->hideTransaction = $hideTransaction;
    }
}
