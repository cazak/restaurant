<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Entity;

use Webmozart\Assert\Assert;

final class CustomerId
{
    private readonly string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
