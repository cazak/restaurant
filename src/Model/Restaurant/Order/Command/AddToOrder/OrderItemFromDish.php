<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Command\AddToOrder;

use App\Model\Restaurant\Establishment\Entity\Dish;
use App\Model\Restaurant\Order\Entity\Order;
use App\Model\Restaurant\Order\Entity\OrderItem;
use DateTimeImmutable;

final class OrderItemFromDish
{
    private const DEFAULT_QUANTITY = 1;

    public function create(Dish $dish, Order $order): OrderItem
    {
        return new OrderItem(
            $dish->getId(),
            new DateTimeImmutable(),
            $order,
            $dish->getPrice(),
            $dish->getName(),
            self::DEFAULT_QUANTITY
        );
    }
}
