<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Query\GetOrder;

use Webmozart\Assert\Assert;

final class GetOrderQuery
{
    public function __construct(public readonly string $orderId)
    {
        Assert::uuid($this->orderId);
    }
}
