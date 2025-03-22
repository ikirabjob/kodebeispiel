<?php

namespace App\Domain\Person\Repository;

use App\Domain\Person\Exception\ContactNotFoundException;
use App\Domain\Person\Model\Contact;
use App\Domain\Shared\Criteria\Criteria;
use Symfony\Component\Uid\Uuid;

interface ContactRepositoryInterface
{
    public function search(Uuid $contactId): ?Contact;

    public function save(Contact $contact): void;

    /**
     * @throws ContactNotFoundException
     */
    public function delete(Uuid $contactId): void;

    public function getPersonContactsByType(Uuid $personId, int $contactType): array;

    public function getPersonOfficeEmailContact(Uuid $personId): ?Contact;

    public function searchByCriteria(Criteria $criteria): iterable;
}
