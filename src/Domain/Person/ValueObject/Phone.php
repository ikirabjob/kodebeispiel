<?php

namespace App\Domain\Person\ValueObject;

use Webmozart\Assert\Assert;

final class Phone implements \JsonSerializable
{
    private string $phone;

    private function __construct(string $phone)
    {
        $this->phone = $phone;
    }

    public static function fromString(string $phone): self
    {
        // $pattern = '/^[0-9]{4,12}$/';
        $pattern = '/^[1-9][0-9]{7,14}$/';

        Assert::regex($phone, $pattern, 'Not a valid phone number');

        return new self($phone);
    }

    public function toString(): string
    {
        return $this->phone;
    }

    public function __toString(): string
    {
        return $this->phone;
    }

    public function jsonSerialize(): string
    {
        return $this->toString();
    }
}
