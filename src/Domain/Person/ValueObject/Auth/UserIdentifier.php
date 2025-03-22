<?php

namespace App\Domain\Person\ValueObject\Auth;

use App\Domain\Person\Enums\LoginTypeEnum;
use App\Domain\Person\ValueObject\Email;
use App\Domain\Person\ValueObject\Phone;
use Webmozart\Assert\InvalidArgumentException;

final class UserIdentifier
{
    private Email|Phone|string $value;

    private int $type;

    private function __construct(Email|Phone|string $value, int $type)
    {
        $this->value = $value;
        $this->type = $type;
    }

    public static function create(string $value, int $type): self
    {
        if ($type === LoginTypeEnum::LOGIN_TYPE_LEGACY->value) {
            return new self($value, $type);
        } elseif ($type === LoginTypeEnum::LOGIN_TYPE_EMAIL->value) {
            return new self(Email::fromString($value), $type);
        } elseif ($type === LoginTypeEnum::LOGIN_TYPE_PHONE_NUMBER->value) {
            return new self(Phone::fromString($value), $type);
        }

        throw new InvalidArgumentException('Wrong type');
    }

    public function toString(): string
    {
        return is_string($this->value) ? $this->value : $this->value->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function getValue(): Email|Phone|string
    {
        return $this->value;
    }

    public function getType(): int
    {
        return $this->type;
    }
}
