<?php

namespace App\Domain\Person\ValueObject;

final class Profile
{
    public function __construct(
        private ?string $mailingLang,
        private ?int $newsLetter,
        private ?int $eventLetter,
        private ?int $confirmationCode,
        private ?int $securityCodes,
        private ?int $hideBalance,
        private ?int $hideContract,
        private ?int $hideTransaction
    ) {
    }

    public function getMailingLang(): ?string
    {
        return $this->mailingLang;
    }

    public function setMailingLang(?string $mailingLang): void
    {
        $this->mailingLang = $mailingLang;
    }

    public function getNewsLetter(): ?int
    {
        return $this->newsLetter;
    }

    public function setNewsLetter(?int $newsLetter): void
    {
        $this->newsLetter = $newsLetter;
    }

    public function getEventLetter(): ?int
    {
        return $this->eventLetter;
    }

    public function setEventLetter(?int $eventLetter): void
    {
        $this->eventLetter = $eventLetter;
    }

    public function getConfirmationCode(): ?int
    {
        return $this->confirmationCode;
    }

    public function setConfirmationCode(?int $confirmationCode): void
    {
        $this->confirmationCode = $confirmationCode;
    }

    public function getSecurityCodes(): ?int
    {
        return $this->securityCodes;
    }

    public function setSecurityCodes(?int $securityCodes): void
    {
        $this->securityCodes = $securityCodes;
    }

    public function getHideBalance(): ?int
    {
        return $this->hideBalance;
    }

    public function setHideBalance(?int $hideBalance): void
    {
        $this->hideBalance = $hideBalance;
    }

    public function getHideContract(): ?int
    {
        return $this->hideContract;
    }

    public function setHideContract(?int $hideContract): void
    {
        $this->hideContract = $hideContract;
    }

    public function getHideTransaction(): ?int
    {
        return $this->hideTransaction;
    }

    public function setHideTransaction(?int $hideTransaction): void
    {
        $this->hideTransaction = $hideTransaction;
    }
}
