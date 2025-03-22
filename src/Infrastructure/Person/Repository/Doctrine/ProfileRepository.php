<?php

namespace App\Infrastructure\Person\Repository\Doctrine;

use App\Domain\Person\Model\Profile;
use App\Domain\Person\Repository\ProfileRepositoryInterface;
use App\Infrastructure\Shared\Repository\AbstractRepository;
use App\Infrastructure\Shared\Repository\SearchByCriteriaTrait;
use Symfony\Component\Uid\Uuid;

class ProfileRepository extends AbstractRepository implements ProfileRepositoryInterface
{
    use SearchByCriteriaTrait;

    public function search(Uuid $id): ?Profile
    {
        return $this->find($id);
    }

    public function save(Profile $profile): void
    {
        $this->getEntityManager()->persist($profile);
        $this->getEntityManager()->flush();
    }

    public function delete(Uuid $id): void
    {
        $this->getEntityManager()->remove($this->search($id));
        $this->getEntityManager()->flush();
    }

    public function getProfileByPersonId(Uuid $personId): ?Profile
    {
        return $this->findOneBy(['person' => $personId]);
    }

    protected function getClass(): string
    {
        return Profile::class;
    }
}
