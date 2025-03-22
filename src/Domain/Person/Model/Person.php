<?php

namespace App\Domain\Person\Model;

use App\Domain\Person\Enums\PersonTypeEnum;
use App\Domain\Person\ValueObject\UserName;
use App\Domain\Shared\Traits\Doctrine\Timestampable;
use App\Domain\Shared\ValueObject\Stamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Person
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $personId;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $status;

    #[ORM\Embedded(class: UserName::class, columnPrefix: false)]
    private UserName $userName;

    #[ORM\Column(type: 'json', nullable: true, options: ['jsonb' => true])]
    private ?array $profile = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['jsonb' => true])]
    private ?array $notes = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['jsonb' => true])]
    private ?array $legacyMarker = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment;

    #[ORM\Column(type: 'float', options: ['default' => 0])]
    private float $personalPoints;

    #[ORM\Column(type: 'float', options: ['default' => 0])]
    private float $structurePoints;

    #[ORM\OneToOne(mappedBy: 'person', targetEntity: Login::class, cascade: ['all'], orphanRemoval: true)]
    private Login $login;

    #[ORM\OneToMany(mappedBy: 'person', targetEntity: Contact::class)]
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'person_id')]
    private Collection $contacts;

    #[ORM\OneToMany(mappedBy: 'parentPerson', targetEntity: Person::class)]
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'parent_person_id')]
    private Collection $children;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_person_id', referencedColumnName: 'person_id', nullable: true)]
    private ?Person $parentPerson;

    #[ORM\Column(type: 'smallint', nullable: false, options: ['default' => 0])]
    private int $onboardingStep = 0;

    #[ORM\Column(type: 'smallint', nullable: false, options: ['default' => 0])]
    private int $adminInvite = 0;

    #[ORM\Column(type: 'smallint', nullable: false, options: ['default' => 0])]
    private int $inputErrors = 0;

    #[ORM\Column(type: 'smallint', nullable: false, options: ['default' => 0])]
    private int $reviseStatus = 0;

    #[ORM\Column(type: 'smallint', nullable: false, options: ['default' => 3])]
    private int $personType;

    #[ORM\Column(type: 'object_json', nullable: true, options: ['jsonb' => true, 'className' => Stamp::class])]
    private ?Stamp $publicAgreementStamp = null;

    private function __construct(
        Uuid $personId,
        ?Person $parentPerson,
        ?int $status,
        UserName $userName,
        int $personType = PersonTypeEnum::NEW->value
    ) {
        $this->personId = $personId;
        $this->status = $status;
        $this->userName = $userName;
        $this->parentPerson = $parentPerson;
        $this->contacts = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->personalPoints = 0;
        $this->structurePoints = 0;
        $this->personType = $personType;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function setUserName(UserName $userName): void
    {
        $this->userName = $userName;
    }

    public function getOnboardingStep(): int
    {
        return $this->onboardingStep;
    }

    public function setOnboardingStep(int $onboardingStep): void
    {
        $this->onboardingStep = $onboardingStep;
    }

    public function getReviseStatus(): int
    {
        return $this->reviseStatus;
    }

    public function setReviseStatus(int $reviseStatus): void
    {
        $this->reviseStatus = $reviseStatus;
    }

    public static function create(
        ?Person $parentPerson,
        int $status,
        UserName $userName,
        int $personType = PersonTypeEnum::NEW->value,
    ): Person {
        return new self(
            new UuidV4(),
            $parentPerson,
            $status,
            $userName,
            $personType
        );
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function getParentPerson(): ?Person
    {
        return $this->parentPerson;
    }

    public function setProfile(?array $profile): void
    {
        $this->profile = $profile;
    }

    public function getProfile(): ?array
    {
        return $this->profile;
    }

    public function getLogin(): ?Login
    {
        return $this->login;
    }

    public function getPersonId(): Uuid
    {
        return $this->personId;
    }

    public function setLogin(Login $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function addChildren(Person $person): self
    {
        if (!$this->children->contains($person)) {
            $this->children->add($person);
            $person->setParentPerson($this);
        }

        return $this;
    }

    public function setParentPerson(Person $parentPerson): void
    {
        $this->parentPerson = $parentPerson;
    }

    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setPerson($this);
        }

        return $this;
    }

    public function getUserName(): UserName
    {
        return $this->userName;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    public function getNotes(): ?array
    {
        return $this->notes;
    }

    public function getLegacyMarker(): ?array
    {
        return $this->legacyMarker;
    }

    public function getLegacyId(): ?int
    {
        return $this->legacyMarker['platform_user_id'] ?? null;
    }

    /**
     * @return $this
     */
    public function setLegacyMarker(?array $legacyMarker): self
    {
        $this->legacyMarker = $legacyMarker;

        return $this;
    }

    public function getPersonalPoints(): float
    {
        return $this->personalPoints;
    }

    public function increasePersonalPoints(float $points): self
    {
        $this->personalPoints += $points;

        return $this;
    }

    public function increaseStructurePoints(float $points): self
    {
        $this->structurePoints += $points;

        return $this;
    }

    public function getTotalPoints(): float
    {
        return $this->personalPoints + $this->structurePoints;
    }

    public function getStructurePoints(): float
    {
        return $this->structurePoints;
    }

    /**
     * @return $this
     */
    public function changePersonalPoints(float $personalPoints): self
    {
        $this->personalPoints = $personalPoints;

        return $this;
    }

    /**
     * @return $this
     */
    public function changeStructurePoints(float $structurePoints): self
    {
        $this->structurePoints = $structurePoints;

        return $this;
    }

    public function getPersonType(): int
    {
        return $this->personType;
    }

    public function setPersonType(int $personType): self
    {
        $this->personType = $personType;

        return $this;
    }

    public function setPublicAgreementStamp(?Stamp $publicAgreementStamp): void
    {
        $this->publicAgreementStamp = $publicAgreementStamp;
    }

    public function getInputErrors(): int
    {
        return $this->inputErrors;
    }

    public function setInputErrors(int $inputErrors): void
    {
        $this->inputErrors = $inputErrors;
    }
}
