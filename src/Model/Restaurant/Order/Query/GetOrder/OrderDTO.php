<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Query\GetOrder;

final class OrderDTO
{
    public function __construct(public readonly string $orderId, public readonly float $price, public readonly string $status)
    {
    }
}
