<?php

namespace App\Domain\Person\Service;

use App\Domain\Person\Enums\ContactTypeEnum;
use App\Domain\Person\Enums\PersonTypeEnum;
use App\Domain\Person\Exception\ContactAlreadyExistsException;
use App\Domain\Person\Exception\WrongContactTypeException;
use App\Domain\Person\Model\Contact;
use App\Domain\Person\Model\Country;
use App\Domain\Person\Model\Person;
use App\Domain\Person\Repository\ContactRepositoryInterface;
use App\Domain\Person\Repository\CountryRepositoryInterface;
use App\Domain\Person\ValueObject\Contact\AddressContact;
use App\Domain\Person\ValueObject\Contact\ContactInterface;
use App\Domain\Person\ValueObject\Contact\ContactMetadata;
use App\Domain\Person\ValueObject\Contact\EmailContact;
use App\Domain\Person\ValueObject\Contact\PhoneContact;
use App\Domain\Shared\Service\ArrayToObjectHydratorServiceInterface;
use Symfony\Component\Uid\UuidV4;

abstract class AbstractContactManager implements ContactManagerInterface
{
    public function __construct(
        private readonly ContactRepositoryInterface $contactRepository,
        private readonly ArrayToObjectHydratorServiceInterface $arrayToObjectHydratorService,
        private readonly CountryRepositoryInterface $countryRepository
    ) {
    }

    public function persistContact(
        Person $person,
        ContactTypeEnum $contactType,
        ContactInterface $contact,
        ContactMetadata $contactMetadata
    ): bool {
        if (!$contact->isValid()) {
            return false;
        }

        // TODO temporary solution
        $oldContacts = $this->getPersonContactsByType($person, $contactType);
        if (!empty($oldContacts)) {
            if ($person->getPersonType() === PersonTypeEnum::OLD->value) {
                $this->deleteOldContacts($oldContacts);
            } else {
                throw new ContactAlreadyExistsException('Contact already exist');
            }
        }

        $contact = new Contact(
            new UuidV4(),
            $person,
            $contactType->value,
            $contact->jsonSerialize(),
            $contactMetadata->isVerified(),
            $contactMetadata->isActive(),
            $contactMetadata->isPrimary()
        );

        $this->contactRepository->save($contact);

        return true;
    }

    public function deleteOldContacts(?array $contacts): void
    {
        if (null !== $contacts) {
            /** @var Contact $contact */
            foreach ($contacts as $contact) {
                $this->contactRepository->delete($contact->getContactId());
            }
        }
    }

    public function getPersonDefaultContactByType(Person $person, ContactTypeEnum $contactType): ?ContactInterface
    {
        $contacts = array_filter(
            $this->getPersonContactsByType($person, $contactType),
            fn (Contact $contact): bool => $contact->isPrimary() && $contact->isActive()
        );

        return !empty($contacts) ? $this->transform($contacts[0]) : null;
    }

    public function getPersonContactsByType(Person $person, ContactTypeEnum $contactType): array
    {
        return $this
            ->contactRepository
            ->getPersonContactsByType($person->getPersonId(), $contactType->value);
    }

    public function prepareContactContent(int $contactType, ?array $content): ?ContactInterface
    {
        if (null === $content) {
            return null;
        }

        switch ($contactType) {
            case ContactTypeEnum::POSTAL_ADDRESS->value:
            case ContactTypeEnum::REGISTRATION_ADDRESS->value:
                $className = AddressContact::class;
                break;
            case ContactTypeEnum::OFFICE_EMAIL->value:
            case ContactTypeEnum::PERSONAL_EMAIL->value:
                $className = EmailContact::class;
                break;
            case ContactTypeEnum::PERSONAL_PHONE->value:
                $className = PhoneContact::class;
                break;

            default:
                throw new WrongContactTypeException();
        }

        try {
            $instance = $this->arrayToObjectHydratorService->hydrate($content, $className);
        } catch (\ReflectionException $e) {
            throw new \InvalidArgumentException(sprintf('Wrong document parameters. (%s)', $e->getMessage()));
        }

        if (!$instance->isValid()) {
            throw new \InvalidArgumentException('Wrong document parameters');
        }

        return $instance;
    }

    protected function transform(Contact $contact): ContactInterface
    {
        switch ($contact->getContactType()) {
            case ContactTypeEnum::POSTAL_ADDRESS->value:
            case ContactTypeEnum::REGISTRATION_ADDRESS->value:
                $className = AddressContact::class;
                break;
            case ContactTypeEnum::OFFICE_EMAIL->value:
            case ContactTypeEnum::PERSONAL_EMAIL->value:
                $className = EmailContact::class;
                break;
            case ContactTypeEnum::PERSONAL_PHONE->value:
                $className = PhoneContact::class;
                break;

            default:
                throw new WrongContactTypeException();
        }

        return $this
            ->arrayToObjectHydratorService
            ->hydrate($contact->getContent(), $className);
    }

    public function transformContactContent(?array $content): ?array
    {
        if (null !== $content) {
            if (array_key_exists('countryId', $content)) {
                /** @var Country $country */
                $country = $this->countryRepository->search(UuidV4::fromString($content['countryId']));
                $content['countryName'] = $country?->getName();
            }
        }

        return $content;
    }
}
