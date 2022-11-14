<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Establishment\Command\CreateDish;

use Webmozart\Assert\Assert;

final class CreateDishCommand
{
    public function __construct(
        public readonly string $name,
        public readonly float $price,
    ) {
        Assert::notEmpty($name);
        Assert::notEmpty($price);
        Assert::float($price);
    }
}
