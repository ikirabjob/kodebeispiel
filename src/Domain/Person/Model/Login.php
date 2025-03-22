<?php

namespace App\Domain\Person\Model;

use App\Domain\Person\Enums\PersonStatusEnum;
use App\Domain\Person\Enums\PersonTypeEnum;
use App\Domain\Person\ValueObject\Auth\Credentials;
use App\Domain\Person\ValueObject\Auth\UserIdentifier;
use App\Domain\Person\ValueObject\UserName;
use App\Domain\Shared\Traits\Doctrine\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Login
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $loginId;

    #[ORM\Column(type: 'smallint', options: ['default' => 0])]
    private int $loginType;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $loginName;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $password;

    #[ORM\Column(type: 'json', nullable: true, options: ['jsonb' => true])]
    private ?array $notes = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['jsonb' => true])]
    private ?array $legacyMarker = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['jsonb' => true])]
    private ?array $options = null;

    #[ORM\OneToOne(mappedBy: 'login', targetEntity: Person::class, cascade: ['all'])]
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'person_id', nullable: false)]
    private Person $person;

    #[ORM\Column(type: 'json', nullable: true, options: ['jsonb' => true])]
    private ?array $roles;

    private function __construct(
        Uuid $loginId,
        int $loginType,
        string $loginName,
        string $password,
        Person $person,
    ) {
        $this->loginId = $loginId;
        $this->loginType = $loginType;
        $this->loginName = $loginName;
        $this->password = $password;
        $this->person = $person;
    }

    public static function create(
        Uuid $uuid,
        Credentials $credentials,
        Person $parentPerson,
        int $personType = PersonTypeEnum::NEW->value,
    ): self {
        return new self(
            $uuid,
            $credentials->userIdentifier->getType(),
            $credentials->userIdentifier->toString(),
            $credentials->hashedPassword->toString(),
            Person::create(
                $parentPerson,
                PersonStatusEnum::STATUS_CANDIDATE_NO_AGREEMENT->value,
                new UserName(null, null, null),
                $personType
            )
        );
    }

    public function getLoginId(): Uuid
    {
        return $this->loginId;
    }

    public function getNotes(): ?array
    {
        return $this->notes;
    }

    public function setNotes(?array $notes): void
    {
        $this->notes = $notes;
    }

    public function getLegacyMarker(): ?array
    {
        return $this->legacyMarker;
    }

    /**
     * @return $this
     */
    public function setLegacyMarker(?array $legacyMarker): self
    {
        $this->legacyMarker = $legacyMarker;

        return $this;
    }

    public function getPerson(): Person
    {
        return $this->person;
    }

    public function getId(): Uuid
    {
        return $this->loginId;
    }

    public function getLoginType(): int
    {
        return $this->loginType;
    }

    public function getLoginName(): string
    {
        return $this->loginName;
    }

    /**
     * @return $this
     */
    public function setPerson(Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function changePassword(string $newHashedPassword): self
    {
        $this->password = $newHashedPassword;

        return $this;
    }

    public function changeLogin(UserIdentifier $userIdentifier): self
    {
        $this->loginName = $userIdentifier->getValue();
        $this->loginType = $userIdentifier->getType();

        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * @return $this
     */
    public function setOptions(?array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /**
     * @return $this
     */
    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
