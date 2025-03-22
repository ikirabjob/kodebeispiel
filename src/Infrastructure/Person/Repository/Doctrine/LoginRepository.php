<?php

namespace App\Infrastructure\Person\Repository\Doctrine;

use App\Domain\Person\Model\Login;
use App\Domain\Person\Repository\CheckLoginByUserIdentifierExistsInterface;
use App\Domain\Person\Repository\LoginRepositoryInterface;
use App\Domain\Person\ValueObject\Auth\Credentials;
use App\Domain\Person\ValueObject\Auth\HashedPassword;
use App\Domain\Person\ValueObject\Auth\UserIdentifier;
use App\Infrastructure\Shared\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Uid\Uuid;

class LoginRepository extends AbstractRepository implements LoginRepositoryInterface, CheckLoginByUserIdentifierExistsInterface
{
    /**
     * @throws NonUniqueResultException
     */
    public function getCredentialsByLoginName(string $loginName): array
    {
        $login = $this->getByLoginName($loginName);

        return [
            $login->getId(),
            $login->getPerson(),
            new Credentials(
                UserIdentifier::create($login->getLoginName(), $login->getLoginType()),
                HashedPassword::fromHash($login->getPassword())
            ),
            $login->getRoles(),
        ];
    }

    protected function getClass(): string
    {
        return Login::class;
    }

    public function search(Uuid $id): ?Login
    {
        return $this->find($id);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function searchByPersonId(Uuid $personId): Login
    {
        $qb = $this
            ->createQueryBuilder('l')
            ->where('l.person = :personId')
            ->setParameter('personId', $personId->toRfc4122());

        return $this->oneOrException($qb);
    }

    public function save(Login $login): void
    {
        $this->getEntityManager()->persist($login);
        $this->getEntityManager()->flush();
    }

    public function delete(Uuid $id): void
    {
        $person = $this->search($id);

        $this->getEntityManager()->remove($person);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getByLoginName(string $loginName): Login
    {
        $qb = $this->createQueryBuilder('l');

        $qb
            ->where($qb->expr()->like(
                'l.loginName',
                $qb->expr()->literal($loginName)
            ));

        return $this->oneOrException($qb);
    }

    public function identifierExists(UserIdentifier $userIdentifier): bool
    {
        $qb = $this->createQueryBuilder('l');

        $qb
            ->where($qb->expr()->like(
                'l.loginName',
                $qb->expr()->literal($userIdentifier->toString())
            ));

        return null !== $qb->getQuery()->getOneOrNullResult();
    }
}
