<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Report\Query\TopDishesInPeriodByRevenue;

use App\Model\Restaurant\Order\Entity\OrderStatus;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;

final class TopDishesInPeriodByRevenue
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function daily(): array
    {
        $start = mktime(0, 0, 0, (int)date('m'), (int)date('d'), (int)date('Y'));
        $end = mktime(23, 59, 59, (int)date('m'), (int)date('d'), (int)date('Y'));

        return $this->getQuery($start, $end);
    }

    public function weekly(): array
    {
        $start = mktime(0, 0, 0, (int) date('m'), date('d') - 6, (int) date('Y'));
        $end = mktime(23, 59, 59, (int) date('m'), (int) date('d'), (int) date('Y'));

        return $this->getQuery($start, $end);
    }

    public function monthly(): array
    {
        $start = mktime(0, 0, 0, (int)date('m'), 1, (int)date('Y'));
        $end = mktime(23, 59, 59, (int)date('m'), (int)date('d'), (int)date('Y'));

        return $this->getQuery($start, $end);
    }

    private function getQuery(int $start, int $end): array
    {
        $dateStart = (new DateTimeImmutable)->setTimestamp($start);
        $dateEnd = (new DateTimeImmutable)->setTimestamp($end);

        return $this->connection->createQueryBuilder()
            ->select(['SUM(i.quantity * i.price) AS revenue', 'i.id'])
            ->from('restaurant_order_item', 'i')
//            ->innerJoin('i', 'restaurant_order', 'o',
//                'o.paid_at >= :dateStart
//                AND o.paid_at <= :dateEnd
//                AND o.status = :statusPaid'
//            )
            ->innerJoin('i', 'restaurant_order', 'o', 'o.id = i.order_id')
            ->andWhere('o.paid_at >= :dateStart')
            ->andWhere('o.paid_at <= :dateEnd')
            ->andWhere('o.status = :statusPaid')
            ->setParameter('dateStart', $dateStart->format('Y-m-d H:i:s'))
            ->setParameter('dateEnd', $dateEnd->format('Y-m-d H:i:s'))
            ->setParameter('statusPaid', OrderStatus::STATUS_PAID)
            ->groupBy('i.id')
            ->orderBy('revenue', 'DESC')
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
