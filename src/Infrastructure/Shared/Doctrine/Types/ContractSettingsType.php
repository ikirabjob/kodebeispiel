<?php

namespace App\Infrastructure\Shared\Doctrine\Types;

use App\Domain\Financial\ValueObject\ContractSettings;

class ContractSettingsType extends ObjectJsonType
{
    public const TYPE_NAME = 'contract_settings';
    protected ?string $className = ContractSettings::class;
}
