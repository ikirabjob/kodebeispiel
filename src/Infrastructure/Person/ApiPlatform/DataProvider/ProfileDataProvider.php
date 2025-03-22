<?php

namespace App\Infrastructure\Person\ApiPlatform\DataProvider;

use App\Application\Person\Query\FindProfileQuery;
use App\Application\Person\Query\FindProfilesQuery;
use App\Application\Shared\Query\QueryBusInterface;
use App\Domain\Person\Criteria\ProfileOwnerFilter;
use App\Domain\Person\Model\Profile;
use App\Domain\Shared\ApiPlatform\Pagination\CustomPaginationInterface;
use App\Domain\Shared\Criteria\Criteria;
use App\Infrastructure\Person\ApiPlatform\View\PersonProfileView;
use App\Infrastructure\Person\Auth;
use App\Infrastructure\Shared\ApiPlatform\DataProvider\AbstractDataProvider;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

class ProfileDataProvider extends AbstractDataProvider
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly Security $security
    ) {
    }

    public function getItem(int|string $id): ?PersonProfileView
    {
        /** @var Profile|null $profile */
        $profile = $this->queryBus->ask(new FindProfileQuery($id));

        return null !== $profile ? $this->transform($profile) : null;
    }

    public function getCollection(array $contextFilters = [], array $uriVariables = [])
    {
        /** @var Auth $auth */
        $auth = $this->security->getUser();

        $filters = [];

        if (!$this->security->isGranted('ROLE_ADMINISTRATOR')) {
            if (null !== $auth) {
                $filters[] = new ProfileOwnerFilter($auth->getPersonId()->toRfc4122());
            }
        }

        if ($this->security->isGranted('ROLE_ADMINISTRATOR')
            && null !== $personId = ($contextFilters['personId'] ?? null)) {
            /* @var Uuid|null $personId */
            $filters[] = new ProfileOwnerFilter($personId);
        }

        $page = ($contextFilters['page'] ?? 1);
        /** @var CustomPaginationInterface $profiles */
        $profiles = $this->queryBus->ask(new FindProfilesQuery(new Criteria($filters, [], $page)));

        $result = [];
        foreach ($profiles as $key => $profile) {
            $result[$key] = $this->transform($profile);
        }

        $profiles->setIterator($result);

        return $profiles;
    }

    /**
     * @param Profile $data
     */
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
