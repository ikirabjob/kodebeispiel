<?php

namespace App\Application\Person\Query;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Person\Model\Profile;
use App\Domain\Person\Repository\ProfileRepositoryInterface;
use Symfony\Component\Uid\UuidV4;

class FindProfileByPersonQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly ProfileRepositoryInterface $profileRepository)
    {
    }

    public function __invoke(FindProfileByPersonQuery $query): ?Profile
    {
        return $this->profileRepository->getProfileByPersonId(UuidV4::fromString($query->personId));
    }
}
