<?php

namespace App\Infrastructure\Person\ApiPlatform\DataProvider;

use App\Application\Person\Query\FindPersonQuery;
use App\Application\Shared\Query\QueryBusInterface;
use App\Domain\Dictionary\Criteria\ContractNumberFilter;
use App\Domain\Dictionary\Criteria\CorporateEmailFilter;
use App\Domain\Dictionary\Criteria\EmailFilter;
use App\Domain\Dictionary\Criteria\FirstNameFilter;
use App\Domain\Dictionary\Criteria\LastNameFilter;
use App\Domain\Dictionary\Criteria\LegacyIdFilter;
use App\Domain\Dictionary\Criteria\LegacyIdOrder;
use App\Domain\Dictionary\Criteria\MembershipFeeStatusFilter;
use App\Domain\Dictionary\Criteria\OnboardingStepFilter;
use App\Domain\Dictionary\Criteria\ParentPersonFilter;
use App\Domain\Dictionary\Criteria\PhoneNumberFilter;
use App\Domain\Dictionary\Criteria\ReviseStatusFilter;
use App\Domain\Dictionary\Criteria\StatusFilter;
use App\Domain\Dictionary\QueryBuilder\NativeCriteria;
use App\Domain\Dictionary\Repository\PersonDictionaryRepositoryInterface;
use App\Domain\Person\Model\Person;
use App\Domain\Shared\ApiPlatform\Pagination\CustomPaginationInterface;
use App\Domain\Shared\Exception\AccessDeniedException;
use App\Infrastructure\Person\ApiPlatform\Resource\PersonResource;
use App\Infrastructure\Person\ApiPlatform\View\MeView;
use App\Infrastructure\Person\ApiPlatform\View\PersonView;
use App\Infrastructure\Person\Auth;
use App\Infrastructure\Shared\ApiPlatform\DataProvider\AbstractDataProvider;
use Symfony\Component\Security\Core\Security;

final class PersonDataProvider extends AbstractDataProvider
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly Security $security,
        private readonly PersonDictionaryRepositoryInterface $personDictionaryRepository
    ) {
    }

    public function getCollection(array $contextFilters = [], array $uriVariables = []): iterable
    {
        $filters = [];

        if (null !== $parentPersonId = ($contextFilters['parentPersonId'] ?? null)) {
            $filters[] = new ParentPersonFilter($parentPersonId);
        }

        if (null !== $firstName = ($contextFilters['firstName'] ?? null)) {
            $filters[] = new FirstNameFilter($firstName);
        }

        if (null !== $lastName = ($contextFilters['lastName'] ?? null)) {
            $filters[] = new LastNameFilter($lastName);
        }

        if (null !== $status = ($contextFilters['status'] ?? null)) {
            $filters[] = new StatusFilter($status);
        }

        if (null !== $phoneNumber = ($contextFilters['phoneNumber'] ?? null)) {
            $filters[] = new PhoneNumberFilter($phoneNumber);
        }

        if (null !== $email = ($contextFilters['email'] ?? null)) {
            $filters[] = new EmailFilter($email);
        }

        if (null !== $corporateEmail = ($contextFilters['corporateEmail'] ?? null)) {
            $filters[] = new CorporateEmailFilter($corporateEmail);
        }

        if (null !== $contractNumber = ($contextFilters['contractNumber'] ?? null)) {
            $filters[] = new ContractNumberFilter($contractNumber);
        }

        if (null !== $legacyId = ($contextFilters['legacyId'] ?? null)) {
            $filters[] = new LegacyIdFilter($legacyId);
        }

        if (null !== $notValidatedDocuments = ($contextFilters['onboardingStep'] ?? null)) {
            $filters[] = new OnboardingStepFilter($notValidatedDocuments);
        }

        if (null !== $reviseStatus = ($contextFilters['reviseStatus'] ?? null)) {
            $filters[] = new ReviseStatusFilter($reviseStatus);
        }

        if (null !== $membershipFeeStatus = ($contextFilters['membershipFeeStatus'] ?? null)) {
            $filters[] = new MembershipFeeStatusFilter($membershipFeeStatus);
        }

        $filters[] = new LegacyIdOrder("(p.legacy_marker->>'platform_user_id')::int", 'desc');

        $page = ($contextFilters['page'] ?? 1);

        /** @var CustomPaginationInterface $persons */
        // $persons = $this->queryBus->ask(new FindPersonsQuery(new Criteria($filters, [], $page)));

        $persons = $this
            ->personDictionaryRepository
            ->getFilteredList(new NativeCriteria($filters), $page, false);

        $result = [];
        foreach ($persons as $key => $person) {
            $result[$key] = $this->transform(PersonResource::fromModel($person), true);
        }

        $persons->setIterator($result);

        return $persons;
    }

    public function getItem(int|string $id): null|PersonView|MeView
    {
        if ('me' === $id) {
            return $this->getMe();
        }

        /** @var Person|null $person */
        $person = $this->queryBus->ask(new FindPersonQuery($id));

        return null !== $person ? $this->transform(PersonResource::fromModel($person)) : null;
    }

    /**
     * @param PersonResource $data
     */
    public function transform($data, bool $isCollection = false): PersonView
    {
        $personMembershipFee = null;

        return new PersonView(
            $data->getId(),
            $data->getFirstName(),
            $data->getLastName(),
            $data->getMiddleName(),
            $data->getStatus(),
            $data->getPersonType(),
        );
    }

    /**
     * @throws AccessDeniedException
     */
    private function getMe(): MeView
    {
        $auth = $this->security->getUser();

        if (!$auth instanceof Auth) {
            throw new AccessDeniedException('Access denied');
        }

        $personId = $auth->getPerson()->getPersonId()->toRfc4122();

        return new MeView(
            $auth->getUuid(),
            $auth->getPersonId(),
            $auth->getPerson()->getStatus(),
            $auth->getUserIdentifier(),
            null,
            $auth->getPerson()->getPersonType(),
            null,
            $this->transform(PersonResource::fromModel($auth->getPerson()))
        );
    }
}
