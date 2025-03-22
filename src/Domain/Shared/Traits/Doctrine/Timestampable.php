<?php

namespace App\Domain\Shared\Traits\Doctrine;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
trait Timestampable
{
    #[ORM\Column(type: 'datetimetz_immutable_with_microseconds')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetimetz_immutable_with_microseconds')]
    private \DateTimeInterface $updatedAt;

    private bool $isUpdatableEnabled = true;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        if ($this->isUpdatableEnabled) {
            $this->createdAt = new \DateTimeImmutable();
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function disableUpdatable(): self
    {
        $this->isUpdatableEnabled = false;

        return $this;
    }

    private function enableUpdatable(): self
    {
        $this->isUpdatableEnabled = true;

        return $this;
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        if ($this->isUpdatableEnabled) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return Timestampable
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }
}
