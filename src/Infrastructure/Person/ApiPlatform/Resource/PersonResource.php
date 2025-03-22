<?php

namespace App\Infrastructure\Person\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Domain\Person\Model\Person;
use App\Infrastructure\Person\ApiPlatform\DataProcessor\PersonDataProcessor;
use App\Infrastructure\Person\ApiPlatform\DataProvider\PersonDataProvider;
use App\Infrastructure\Person\ApiPlatform\Payload\PersonPayload;
use App\Infrastructure\Person\ApiPlatform\View\PersonView;
use App\Infrastructure\Shared\ApiPlatform\Filter\SearchFilter;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Person',
    operations: [
        new Get(
            normalizationContext: ['groups' => ['item']],
            // security: 'is_granted("IS_AUTHENTICATED_FULLY")'
        ),
        new Put(
            normalizationContext: ['groups' => ['item']],
            denormalizationContext: ['groups' => ['update']],
            output: false,
        ),
//        new Delete(security: 'is_granted("ROLE_ADMINISTRATOR")'),
        new GetCollection(
            normalizationContext: ['groups' => ['list']],
        ),
        new Post(
            normalizationContext: ['groups' => ['item']],
            denormalizationContext: ['groups' => ['create']],
        ),
    ],
    input: PersonPayload::class,
    output: PersonView::class,
    provider: PersonDataProvider::class,
    processor: PersonDataProcessor::class
)]
#[ApiFilter(
    filterClass: SearchFilter::class,
    properties: [
        'parentPersonId' => 'exact',
        'legacyId' => 'exact',
        'firstName' => 'exact',
        'lastName' => 'exact',
        'status' => 'exact',
        'phoneNumber' => 'exact',
        'email' => 'exact',
        'corporateEmail' => 'exact',
        'contractNumber' => 'exact',
        'onboardingStep' => 'exact',
        'reviseStatus' => 'exact',
        'membershipFeeStatus' => 'exact',
    ]
)]
final class PersonResource
{
    private ?string $id = null;

    private ?string $login;
    private int $status;
    private ?Uuid $parentPersonId;
    private ?string $firstName;
    private ?string $lastName;
    private ?string $middleName;
    private ?string $inviteId;
    private ?int $onboardingStep;
    private ?int $personType;
    private ?int $inviteFromAdmin;
    private ?int $legacyId;
    private ?int $reviseStatus;

    public static function fromModel(Person $model): self
    {
        $resource = new self();
        $resource->id = $model->getPersonId()->toRfc4122();
        $resource->parentPersonId = $model->getParentPerson()?->getPersonId();
        $resource->status = $model->getStatus();
        $resource->firstName = $model->getUserName()->getFirstName();
        $resource->lastName = $model->getUserName()->getLastName();
        $resource->middleName = $model->getUserName()->getMiddleName();
        $resource->onboardingStep = $model->getOnboardingStep();
        $resource->personType = $model->getPersonType();
        $resource->legacyId = $model->getLegacyId();
        $resource->reviseStatus = $model->getReviseStatus();

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

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(?string $middleName): void
    {
        $this->middleName = $middleName;
    }

    public function getPersonType(): ?int
    {
        return $this->personType;
    }

    public function setPersonType(?int $personType): void
    {
        $this->personType = $personType;
    }
}
