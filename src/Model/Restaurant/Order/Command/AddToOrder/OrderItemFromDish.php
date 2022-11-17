<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Command\AddToOrder;

use App\Model\Restaurant\Establishment\Entity\Dish;
use App\Model\Restaurant\Order\Entity\DishId;
use App\Model\Restaurant\Order\Entity\Order;
use App\Model\Restaurant\Order\Entity\OrderItem;
use App\Model\Restaurant\Order\Entity\OrderItemRepository;
use App\Model\Shared\Entity\ValueObject\Id;
use DateTimeImmutable;

final class OrderItemFromDish
{
    private const DEFAULT_QUANTITY = 1;

    public function __construct(private readonly OrderItemRepository $repository)
    {
    }

    public function createOrFind(Dish $dish, Order $order): OrderItem
    {
        $orderItem = $this->repository->findByDish($dish->getId()->getValue());

        if ($orderItem) {
            return $orderItem;
        }

        return new OrderItem(
            Id::next(),
            new DishId($dish->getId()->getValue()),
            new DateTimeImmutable(),
            $order,
            $dish->getPrice(),
            $dish->getName(),
            self::DEFAULT_QUANTITY
        );
    }
}
