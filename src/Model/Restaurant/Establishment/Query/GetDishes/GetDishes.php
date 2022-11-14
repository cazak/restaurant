<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Establishment\Query\GetDishes;

use Doctrine\DBAL\Connection;
use Knp\Component\Pager\PaginatorInterface;

final class GetDishes
{
    public function __construct(private readonly Connection $connection, private readonly PaginatorInterface $paginator)
    {
    }

    public function __invoke(GetDishesQuery $filter): PaginationData
    {
        $qb = $this->connection->createQueryBuilder()
            ->from('restaurant_dish')
            ->select([
                'name',
                'price',
            ]);

        $qb->orderBy($filter->sort, $filter->order);
        $pagination = $this->paginator->paginate($qb, $filter->page, $filter->size);
        $paginationData = $pagination->getPaginationData();

        return new PaginationData(
            $paginationData['totalCount'],
            $paginationData['pageCount'],
            $pagination->getItems(),
        );
    }
}
