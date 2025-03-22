<?php

namespace App\Application\Person\Query;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Person\Model\Person;
use App\Domain\Person\Repository\PersonRepositoryInterface;

final class FindPersonsQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly PersonRepositoryInterface $personRepository)
    {
    }

    /**
     * @return iterable<Person>
     */
    public function __invoke(FindPersonsQuery $query): iterable
    {
        return $this->personRepository->searchByCriteria($query->criteria);
    }
}
