<?php

namespace App\Infrastructure\Person\ApiPlatform\DataProvider;

use App\Application\Person\Query\FindProfileByPersonQuery;
use App\Application\Shared\Query\QueryBusInterface;
use App\Infrastructure\Person\ApiPlatform\View\PersonProfileView;
use App\Infrastructure\Shared\ApiPlatform\DataProvider\AbstractDataProvider;

class PersonProfileDataProvider extends AbstractDataProvider
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
    }

    public function getItem(int|string $id): ?PersonProfileView
    {
        $profile = $this->queryBus->ask(new FindProfileByPersonQuery($id));

        return null !== $profile ? $this->transform($profile) : null;
    }

    public function getCollection(array $contextFilters = [], array $uriVariables = [])
    {
    }

    public function transform($data): PersonProfileView
    {
        return new PersonProfileView(
            $data->getProfileId(),
            $data->getPerson()->getPersonId()->toRfc4122(),
            $data->getMailingLang(),
            $data->getNewsLetter(),
            $data->getEventLetter(),
            $data->getConfirmationCode(),
            $data->getSecurityCodes(),
            $data->getHideBalance(),
            $data->getHideContract(),
            $data->getHideTransaction()
        );
    }
}
