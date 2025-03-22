<?php

namespace App\Infrastructure\Person\ApiPlatform\DataProvider;

use App\Application\Person\Query\FindContactQuery;
use App\Application\Person\Query\FindContactsQuery;
use App\Application\Shared\Query\QueryBusInterface;
use App\Domain\Person\Criteria\ContactOwnerFilter;
use App\Domain\Person\Model\Contact;
use App\Domain\Shared\ApiPlatform\Pagination\CustomPaginationInterface;
use App\Domain\Shared\Criteria\Criteria;
use App\Infrastructure\Person\ApiPlatform\Resource\ContactResource;
use App\Infrastructure\Person\ApiPlatform\View\ContactView;
use App\Infrastructure\Shared\ApiPlatform\DataProvider\AbstractDataProvider;

final class ContactProvider extends AbstractDataProvider
{
    public function __construct(private readonly QueryBusInterface $queryBus)
    {
    }

    public function getCollection(array $contextFilters = [], array $uriVariables = []): iterable
    {
        $filters = [];

        if (null !== $personId = ($contextFilters['personId'] ?? null)) {
            $filters[] = new ContactOwnerFilter($personId);
        }

        $page = ($contextFilters['page'] ?? 1);
        /** @var CustomPaginationInterface $countries */
        $contacts = $this->queryBus->ask(new FindContactsQuery(new Criteria($filters, [], $page)));

        $result = [];
        foreach ($contacts as $key => $contact) {
            $result[$key] = $this->transform(ContactResource::fromModel($contact));
        }

        $contacts->setIterator($result);

        return $contacts;
    }

    public function getItem(int|string $id): ?ContactView
    {
        /** @var Contact|null $contact */
        $contact = $this->queryBus->ask(new FindContactQuery($id));

        return null !== $contact ? $this->transform(ContactResource::fromModel($contact)) : null;
    }

    /**
     * @param ContactResource $data
     */
    public function transform($data): ContactView
    {
        return new ContactView(
            $data->getId(),
            $data->getPersonId(),
            $data->getContactType(),
            $data->getContent(),
            $data->getIsVerified(),
            $data->getIsActive(),
            $data->getIsPrimary()
        );
    }
}
