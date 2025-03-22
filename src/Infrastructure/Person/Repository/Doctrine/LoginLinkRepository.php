<?php

namespace App\Infrastructure\Person\Repository\Doctrine;

use App\Domain\Person\Model\LoginLink;
use App\Domain\Person\Repository\LoginLinkRepositoryInterface;
use App\Infrastructure\Shared\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Uid\Uuid;

class LoginLinkRepository extends AbstractRepository implements LoginLinkRepositoryInterface
{
    protected function getClass(): string
    {
        return LoginLink::class;
    }

    public function search(Uuid $id): ?LoginLink
    {
        return $this->find($id);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function searchByPersonId(Uuid $personId): ?LoginLink
    {
        $qb = $this
            ->createQueryBuilder('l')
            ->where('l.person = :personId')
            ->setParameter('personId', $personId->toRfc4122());

        return $this->oneOrException($qb);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function searchByHash(string $hash): ?LoginLink
    {
        $qb = $this
            ->createQueryBuilder('l')
            ->where('l.hash = :hash')
            ->setParameter('hash', $hash);

        return $this->oneOrException($qb);
    }

    public function save(LoginLink $login): void
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
}
