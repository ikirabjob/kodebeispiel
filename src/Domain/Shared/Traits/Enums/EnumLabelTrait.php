<?php

namespace App\Domain\Shared\Traits\Enums;

trait EnumLabelTrait
{
    public function getLabel(): string
    {
        return $this->name;
    }
}
