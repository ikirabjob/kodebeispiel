<?php

namespace App\Infrastructure\Person\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Domain\Person\Model\Country;
use App\Infrastructure\Person\ApiPlatform\DataProvider\CountryDataProvider;
use App\Infrastructure\Person\ApiPlatform\View\CountryView;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Country',
    operations: [
        new Get(
            normalizationContext: ['groups' => ['item']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY")'
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['list']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY")'
        ),
    ],
    input: false,
    output: CountryView::class,
    paginationEnabled: false,
    provider: CountryDataProvider::class
)]
class CountryResource
{
    #[Assert\Uuid]
    private ?string $id;
    private ?string $name;

    public static function fromModel(Country $model): self
    {
        $resource = new self();
        $resource->id = $model->getCountryId()->toRfc4122();
        $resource->name = $model->getName();

        return $resource;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
