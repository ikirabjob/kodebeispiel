<?php

namespace App\Infrastructure\Person\ApiPlatform\DataProvider;

use App\Application\Person\Query\FindCountriesQuery;
use App\Application\Person\Query\FindCountryQuery;
use App\Application\Shared\Query\QueryBusInterface;
use App\Domain\Person\Model\Country;
use App\Domain\Shared\ApiPlatform\Pagination\CustomPaginationInterface;
use App\Domain\Shared\Criteria\Criteria;
use App\Infrastructure\Person\ApiPlatform\Resource\CountryResource;
use App\Infrastructure\Person\ApiPlatform\View\CountryView;
use App\Infrastructure\Shared\ApiPlatform\DataProvider\AbstractDataProvider;

final class CountryDataProvider extends AbstractDataProvider
{
    public function __construct(private readonly QueryBusInterface $queryBus)
    {
    }

    public function getCollection(array $contextFilters = [], array $uriVariables = []): iterable
    {
        $page = ($contextFilters['page'] ?? 1);
        /** @var CustomPaginationInterface $countries */
        $countries = $this->queryBus->ask(new FindCountriesQuery(new Criteria([], [], $page, true)));

        $result = [];
        foreach ($countries as $key => $country) {
            $result[$key] = $this->transform(CountryResource::fromModel($country));
        }

        // $countries->setIterator($result);

        return $result;
    }

    public function getItem(int|string $id): ?CountryView
    {
        /** @var Country|null $country */
        $country = $this->queryBus->ask(new FindCountryQuery($id));

        return null !== $country ? $this->transform(CountryResource::fromModel($country)) : null;
    }

    public function transform($data): CountryView
    {
        return new CountryView(
            $data->getId(),
            $data->getName()
        );
    }
}
