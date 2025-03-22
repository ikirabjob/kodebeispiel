<?php

namespace App\Domain\Person\Service;

use App\Domain\Person\Enums\ContactTypeEnum;
use App\Domain\Person\Model\Person;
use App\Domain\Person\ValueObject\Contact\ContactInterface;
use App\Domain\Person\ValueObject\Contact\ContactMetadata;

interface ContactManagerInterface
{
    public function persistContact(
        Person $person,
        ContactTypeEnum $contactType,
        ContactInterface $contact,
        ContactMetadata $contactMetadata
    ): bool;

    public function getPersonDefaultContactByType(Person $person, ContactTypeEnum $contactType): ?ContactInterface;

    public function getPersonContactsByType(Person $person, ContactTypeEnum $contactType): array;

    public function prepareContactContent(
        int $contactType,
        ?array $content
    ): ?ContactInterface;
}
