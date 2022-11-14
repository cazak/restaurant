<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Establishment\Query\GetDishes;

use Webmozart\Assert\Assert;

final class GetDishesQuery
{
    public function __construct(
        public readonly string $sort = 'name',
        public readonly string $order = 'asc',
        public readonly int $size = 10,
        public readonly int $page = 1,
    ) {
        Assert::oneOf($this->sort, ['name', 'price']);
        Assert::oneOf($this->order, ['asc', 'desc']);
        Assert::greaterThan($size, 1);
        Assert::greaterThanEq($page, 1);
    }
}
