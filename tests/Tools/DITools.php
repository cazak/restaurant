<?php

declare(strict_types=1);

namespace App\Tests\Tools;

trait DITools
{
    public function getService(string $service)
    {
        return static::getContainer()->get($service);
    }
}
