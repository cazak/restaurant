<?php

declare(strict_types=1);

namespace App\Model\Infrastructure\Event\Dispatcher;

use App\Model\Infrastructure\Event\Dispatcher\Message\Message;
use App\Model\Shared\Entity\Event\Event;
use App\Model\Shared\EventDispatcher\EventDispatcher;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerEventDispatcher implements EventDispatcher
{
    public function __construct(private readonly MessageBusInterface $bus)
    {
    }

    /**
     * @param Event[] $events
     */
    public function dispatch(array $events): void
    {
        foreach ($events as $event) {
            $this->bus->dispatch(new Message($event));
        }
    }
}
