<?php

namespace App\Domain\Person\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $profileId;

    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ['default' => 'ru'])]
    private ?string $mailingLang;

    #[ORM\Column(type: 'smallint', nullable: true, options: ['default' => 1])]
    private ?int $newsLetter;

    #[ORM\Column(type: 'smallint', nullable: true, options: ['default' => 1])]
    private ?int $eventLetter;

    #[ORM\Column(type: 'smallint', nullable: true, options: ['default' => 1])]
    private ?int $confirmationCode;

    #[ORM\Column(type: 'smallint', nullable: true, options: ['default' => 1])]
    private ?int $securityCodes;

    #[ORM\Column(type: 'smallint', nullable: true, options: ['default' => 1])]
    private ?int $hideBalance;

    #[ORM\Column(type: 'smallint', nullable: true, options: ['default' => 1])]
    private ?int $hideContract;

    #[ORM\Column(type: 'smallint', nullable: true, options: ['default' => 1])]
    private ?int $hideTransaction;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'companies')]
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'person_id', nullable: false)]
    private Person $person;

    private function __construct(
        Uuid $profileId,
        ?Person $person
    ) {
        $this->profileId = $profileId;
        $this->person = $person;
        $this->mailingLang = 'ru';
        $this->newsLetter = 1;
        $this->eventLetter = 1;
        $this->confirmationCode = 1;
        $this->securityCodes = 1;
        $this->hideBalance = 1;
        $this->hideContract = 1;
        $this->hideTransaction = 1;
    }

    public static function create(
        ?Person $person
    ): Profile {
        return new self(
            new UuidV4(),
            $person
        );
    }

    public function getProfileId(): Uuid
    {
        return $this->profileId;
    }

    public function setProfileId(Uuid $profileId): void
    {
        $this->profileId = $profileId;
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

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): void
    {
        $this->person = $person;
    }
}
