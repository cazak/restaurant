<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Command\Pay;

use Webmozart\Assert\Assert;

final class OrderPayCommand
{
    public function __construct(
        public readonly string $customerId,
        public readonly string $orderId,
    ) {
        Assert::uuid($this->customerId);
        Assert::uuid($this->orderId);
    }
}
