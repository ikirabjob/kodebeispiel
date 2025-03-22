<?php

namespace App\Application\Person\Query;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Person\Model\Country;
use App\Domain\Person\Repository\CountryRepositoryInterface;

final class FindCountriesQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly CountryRepositoryInterface $countryRepository)
    {
    }

    /**
     * @return iterable<Country>
     */
    public function __invoke(FindCountriesQuery $query): iterable
    {
        return $this->countryRepository->searchByCriteria($query->criteria);
    }
}
