<?php

namespace App\Domain\Shared\ValueObject;

final class Stamp implements \JsonSerializable
{
    public function __construct(
        private readonly string $personId,
        private readonly array $fingerprint,
        private readonly string $ip,
        private readonly string $hostname,
        private readonly string $timestamp
    ) {
    }

    public function getPersonId(): string
    {
        return $this->personId;
    }

    public function getFingerprint(): array
    {
        return $this->fingerprint;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    public function jsonSerialize(): array
    {
        return [
            'personId' => $this->personId,
            'fingerprint' => $this->fingerprint,
            'ip' => $this->ip,
            'hostname' => $this->hostname,
            'timestamp' => $this->timestamp,
        ];
    }
}
