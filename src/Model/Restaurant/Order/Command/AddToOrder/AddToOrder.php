<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Command\AddToOrder;

use App\Model\Infrastructure\Flush;
use App\Model\Restaurant\Establishment\Entity\DishRepository;
use App\Model\Restaurant\Order\Entity\OrderItemRepository;
use App\Model\Restaurant\Order\Entity\OrderRepository;
use App\Model\Restaurant\Order\Factory\OrderFactory;

final class AddToOrder
{
    public function __construct(
        private readonly OrderFactory $factory,
        private readonly OrderRepository $orderRepository,
        private readonly OrderItemRepository $orderItemRepository,
        private readonly DishRepository $dishRepository,
        private readonly Flush $flush,
        private readonly OrderItemFromDish $orderItemFromDish,
    ) {
    }

    public function __invoke(AddToOrderCommand $command): string
    {
        $dish = $this->dishRepository->get($command->dishId);
        $order = $this->orderRepository->findByCustomer($command->customerId);

        if (!$order) {
            $order = $this->factory->create($command->customerId);
            $this->orderRepository->add($order);
        }

        $orderItem = $this->orderItemFromDish->createOrFind($dish, $order);
        $order->modify($orderItem);

        $this->orderItemRepository->add($orderItem);
        ($this->flush)();

        return $order->getId()->getValue();
    }
}
