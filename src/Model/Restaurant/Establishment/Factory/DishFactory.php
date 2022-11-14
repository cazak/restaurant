<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Establishment\Factory;

use App\Model\Restaurant\Establishment\Entity\Dish;
use App\Model\Restaurant\Shared\Entity\ValueObject\Id;
use DateTimeImmutable;

final class DishFactory
{
    public function create(string $name, float $price, ?DateTimeImmutable $date = null): Dish
    {
        return new Dish(
            Id::next(),
            $date ?? new DateTimeImmutable(),
            $price,
            $name,
        );
    }
}
