<?php

namespace App\Infrastructure\Person\Repository\Doctrine;

use App\Domain\Person\Enums\ContactTypeEnum;
use App\Domain\Person\Model\Contact;
use App\Domain\Person\Repository\ContactRepositoryInterface;
use App\Infrastructure\Shared\Repository\AbstractRepository;
use App\Infrastructure\Shared\Repository\SearchByCriteriaTrait;
use Symfony\Component\Uid\Uuid;

class ContactRepository extends AbstractRepository implements ContactRepositoryInterface
{
    use SearchByCriteriaTrait;

    public function search(Uuid $contactId): ?Contact
    {
        return $this->find($contactId);
    }

    public function save(Contact $contact): void
    {
        $this->getEntityManager()->persist($contact);
        $this->getEntityManager()->flush();
    }

    public function delete(Uuid $contactId): void
    {
        $this->getEntityManager()->remove($this->search($contactId));
        $this->getEntityManager()->flush();
    }

    public function getPersonContactsByType(Uuid $personId, int $contactType): array
    {
        return $this
            ->findBy([
                'person' => $personId,
                'contactType' => $contactType,
            ]);
    }

    public function getPersonOfficeEmailContact(Uuid $personId): ?Contact
    {
        return $this
            ->findOneBy([
                'person' => $personId,
                'contactType' => ContactTypeEnum::OFFICE_EMAIL->value,
            ]);
    }

    protected function getClass(): string
    {
        return Contact::class;
    }
}
