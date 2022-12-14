<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Command\AddToOrder;

use Webmozart\Assert\Assert;

final class AddToOrderCommand
{
    public function __construct(
        public readonly string $dishId,
        public readonly string $customerId,
    ) {
        Assert::uuid($this->dishId);
        Assert::uuid($this->customerId);
    }
}
