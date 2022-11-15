<?php

declare(strict_types=1);

namespace App\Model\Infrastructure\Event\Dispatcher\Message;

use App\Model\Shared\Entity\Event\Event;

final class Message
{
    public function __construct(private readonly Event $event)
    {
    }

    public function getEvent(): Event
    {
        return $this->event;
    }
}
