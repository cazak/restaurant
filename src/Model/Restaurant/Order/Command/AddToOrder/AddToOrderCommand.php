<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Command\AddToOrder;

final class AddToOrderCommand
{
    public function __construct(
        public readonly string $dishId,
        public readonly string $customerId,
    ) {
    }
}
