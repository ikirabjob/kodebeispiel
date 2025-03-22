<?php

namespace App\Domain\Person\Model;

use App\Domain\Shared\Traits\Doctrine\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Contact
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $contactId;

    #[ORM\Column(type: 'smallint', nullable: false)]
    private int $contactType;

    #[ORM\Column(type: 'json', nullable: true, options: ['jsonb' => true])]
    private ?array $content;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private bool $isPrimary;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private bool $isActive;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private bool $isVerified;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $status;

    #[ORM\Column(type: 'json', nullable: true, options: ['jsonb' => true])]
    private ?array $notes;

    #[ORM\Column(type: 'json', nullable: true, options: ['jsonb' => true])]
    private ?array $legacyMarker;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'contacts')]
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'person_id', nullable: false)]
    private Person $person;

    public function __construct(
        Uuid $contactId,
        Person $person,
        int $contactType,
        ?array $content,
        bool $isVerified,
        bool $isActive,
        bool $isPrimary
    ) {
        $this->contactId = $contactId;
        $this->person = $person;
        $this->contactType = $contactType;
        $this->content = $content;
        $this->isVerified = $isVerified;
        $this->isActive = $isActive;
        $this->isPrimary = $isPrimary;
    }

    public function setPerson(Person $person): void
    {
        $this->person = $person;
    }

    /**
     * @return $this
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return $this
     */
    public function setNotes(?array $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return $this
     */
    public function setLegacyMarker(?array $legacyMarker): self
    {
        $this->legacyMarker = $legacyMarker;

        return $this;
    }

    public function getContactId(): Uuid
    {
        return $this->contactId;
    }

    public function getContactType(): int
    {
        return $this->contactType;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    /**
     * @return $this
     */
    public function setContent(?array $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function isPrimary(): bool
    {
        return $this->isPrimary;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getNotes(): ?array
    {
        return $this->notes;
    }

    public function getLegacyMarker(): ?array
    {
        return $this->legacyMarker;
    }

    public function getPerson(): Person
    {
        return $this->person;
    }
}
