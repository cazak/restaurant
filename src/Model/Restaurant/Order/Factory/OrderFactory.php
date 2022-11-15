<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Factory;

use App\Model\Restaurant\Order\Entity\CustomerId;
use App\Model\Restaurant\Order\Entity\Order;
use App\Model\Shared\Entity\ValueObject\Id;
use DateTimeImmutable;

final class OrderFactory
{
    public function create(string $customerId, ?DateTimeImmutable $date = null): Order
    {
        return new Order(
            Id::next(),
            new CustomerId($customerId),
            $date ?? new DateTimeImmutable(),
        );
    }
}
