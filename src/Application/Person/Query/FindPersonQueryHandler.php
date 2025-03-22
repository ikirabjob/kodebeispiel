<?php

namespace App\Application\Person\Query;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Person\Model\Person;
use App\Domain\Person\Repository\PersonRepositoryInterface;
use Symfony\Component\Uid\UuidV4;

final class FindPersonQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly PersonRepositoryInterface $personRepository)
    {
    }

    public function __invoke(FindPersonQuery $query): ?Person
    {
        return $this->personRepository->search(UuidV4::fromString($query->id));
    }
}
