<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandInterface;

final class DeletePersonCommand implements CommandInterface
{
    public function __construct(public string $id)
    {
    }
}
