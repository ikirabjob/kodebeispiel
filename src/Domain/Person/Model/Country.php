<?php

namespace App\Domain\Person\Model;

use App\Domain\Shared\Traits\Doctrine\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Country
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $countryId;

    #[ORM\Column(type: 'json', nullable: true, options: ['jsonb' => true])]
    private ?array $legacyMarker;

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $name;

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $alpha2;

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $alpha3;

    public function __construct(
        Uuid $countryId,
        ?string $name,
        ?string $alpha2,
        ?string $alpha3,
        array $legacyMarker = null,
    ) {
        $this->countryId = $countryId;
        $this->name = $name;
        $this->alpha2 = $alpha2;
        $this->alpha3 = $alpha3;
        $this->legacyMarker = $legacyMarker;
    }

    public function getCountryId(): Uuid
    {
        return $this->countryId;
    }

    public function getLegacyMarker(): ?array
    {
        return $this->legacyMarker;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getAlpha2(): ?string
    {
        return $this->alpha2;
    }

    public function getAlpha3(): ?string
    {
        return $this->alpha3;
    }
}
