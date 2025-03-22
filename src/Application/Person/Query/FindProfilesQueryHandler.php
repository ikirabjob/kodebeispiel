<?php

namespace App\Application\Person\Query;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Person\Model\Profile;
use App\Domain\Person\Repository\ProfileRepositoryInterface;

final class FindProfilesQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly ProfileRepositoryInterface $profileRepository)
    {
    }

    /**
     * @return iterable<Profile>
     */
    public function __invoke(FindProfilesQuery $query): iterable
    {
        return $this->profileRepository->searchByCriteria($query->criteria);
    }
}
