<?php

namespace App\Application\Shared\Command;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): mixed;
}
