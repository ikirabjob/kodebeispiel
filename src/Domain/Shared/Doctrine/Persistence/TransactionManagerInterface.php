<?php

namespace App\Domain\Shared\Doctrine\Persistence;

interface TransactionManagerInterface
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;
}
