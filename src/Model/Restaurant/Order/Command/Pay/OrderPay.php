<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Command\Pay;

use App\Model\Infrastructure\Event\EventStarter;
use App\Model\Infrastructure\Flush;
use App\Model\Restaurant\Order\Entity\OrderRepository;

final class OrderPay
{
    public function __construct(
        private readonly OrderRepository $repository,
        private readonly Flush $flush,
        private readonly EventStarter $eventStarter,
    ) {
    }

    public function __invoke(OrderPayCommand $command): void
    {
        $order = $this->repository->get($command->orderId);

        if ($order->getCustomerId()->getValue() !== $command->customerId) {
            throw new WrongBuyerException();
        }

        $order->pay();

        ($this->flush)();
        $this->eventStarter->release($order);
    }
}
