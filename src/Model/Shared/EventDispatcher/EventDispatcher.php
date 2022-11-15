<?php

namespace App\Model\Shared\EventDispatcher;

use App\Model\Shared\Entity\Event\Event;

interface EventDispatcher
{
    /**
     * @param Event[] $events
     */
    public function dispatch(array $events): void;
}
