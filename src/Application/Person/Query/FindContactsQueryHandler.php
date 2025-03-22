<?php

namespace App\Application\Person\Query;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Person\Model\Contact;
use App\Domain\Person\Repository\ContactRepositoryInterface;

final class FindContactsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly ContactRepositoryInterface $contactRepository
    ) {
    }

    /**
     * @return iterable<Contact>
     */
    public function __invoke(FindContactsQuery $query): iterable
    {
        return $this->contactRepository->searchByCriteria($query->criteria);
    }
}
