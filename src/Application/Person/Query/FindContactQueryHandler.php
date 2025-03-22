<?php

namespace App\Application\Person\Query;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Person\Model\Contact;
use App\Domain\Person\Repository\ContactRepositoryInterface;
use Symfony\Component\Uid\UuidV4;

final class FindContactQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly ContactRepositoryInterface $contactRepository
    ) {
    }

    public function __invoke(FindContactQuery $query): Contact
    {
        return $this->contactRepository->search(UuidV4::fromString($query->id));
    }
}
