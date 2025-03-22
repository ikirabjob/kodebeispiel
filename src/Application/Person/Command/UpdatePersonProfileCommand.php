<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandInterface;

class UpdatePersonProfileCommand implements CommandInterface
{
    public function __construct(
        public string $id,
        public ?string $mailingLang,
        public ?int $newsLetter,
        public ?int $eventLetter,
        public ?int $confirmationCode,
        public ?int $securityCodes,
        public ?int $hideBalance,
        public ?int $hideContract,
        public ?int $hideTransaction,
    ) {
    }
}
