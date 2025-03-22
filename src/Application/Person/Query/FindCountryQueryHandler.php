<?php

namespace App\Application\Person\Query;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Person\Model\Country;
use App\Domain\Person\Repository\CountryRepositoryInterface;
use Symfony\Component\Uid\UuidV4;

final class FindCountryQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly CountryRepositoryInterface $countryRepository)
    {
    }

    public function __invoke(FindCountryQuery $query): ?Country
    {
        return $this->countryRepository->search(UuidV4::fromString($query->id));
    }
}
