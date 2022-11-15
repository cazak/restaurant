<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Query\GetOrder;

use App\Model\Restaurant\Order\Entity\OrderRepository;

final class GetOrder
{
    public function __construct(private readonly OrderRepository $repository)
    {
    }

    public function __invoke(GetOrderQuery $query): OrderDTO
    {
        $order = $this->repository->get($query->orderId);

        return new OrderDTO($order->getId()->getValue(), $order->getPrice(), $order->getStatus()->getValue());
    }
}
