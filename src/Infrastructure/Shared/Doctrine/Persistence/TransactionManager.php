<?php

namespace App\Infrastructure\Shared\Doctrine\Persistence;

use App\Domain\Shared\Doctrine\Persistence\TransactionManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

class TransactionManager implements TransactionManagerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function begin(): void
    {
        $this->entityManager->beginTransaction();
    }

    public function commit(): void
    {
        $this->entityManager->commit();
    }

    public function rollback(): void
    {
        $this->entityManager->rollback();
    }
}
