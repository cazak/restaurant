<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Entity\Event;

use App\Model\Shared\Entity\Event\Event;
use App\Model\Shared\Entity\ValueObject\Id;

final class OrderPaid implements Event
{
    public function __construct(public readonly Id $orderId)
    {
    }
}
