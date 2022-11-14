<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Establishment\Query\GetDishes;

final class PaginationData
{
    public function __construct(
        public readonly int $itemCount,
        public readonly int $pageCount,
        public readonly array $items,
    ) {
    }
}
