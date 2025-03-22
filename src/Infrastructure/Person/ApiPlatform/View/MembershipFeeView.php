<?php

namespace App\Infrastructure\Person\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

final class MembershipFeeView
{
    public function __construct(
        #[Groups(['item', 'list'])]
        public string $id,
        #[Groups(['item', 'list'])]
        public float $amountInEur,
        #[Groups(['item', 'list'])]
        public ?float $amountInUsd,
        #[Groups(['item', 'list'])]
        public int $status,
        #[Groups(['item', 'list'])]
        public ?int $paymentType,
        #[Groups(['item', 'list'])]
        public ?string $address,
        #[Groups(['item', 'list'])]
        public ?\DateTimeInterface $paidAt,
        #[Groups(['item', 'list'])]
        public ?\DateTimeInterface $approvedAt,
        #[Groups(['item', 'list'])]
        public ?string $transactionHash,
    ) {
    }
}
