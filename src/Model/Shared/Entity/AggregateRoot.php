<?php

namespace App\Model\Shared\Entity;

use App\Model\Shared\Entity\Event\Event;

interface AggregateRoot
{
    /**
     * @return Event[]
     */
    public function releaseEvents(): array;
}
