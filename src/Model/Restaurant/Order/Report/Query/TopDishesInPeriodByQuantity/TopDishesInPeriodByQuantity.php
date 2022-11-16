<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Report\Query\TopDishesInPeriodByQuantity;

use App\Model\Restaurant\Order\Entity\OrderStatus;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;

final class TopDishesInPeriodByQuantity
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function daily(): array
    {
        $start = mktime(0, 0, 0, (int) date('m'), (int) date('d'), (int) date('Y'));
        $end = mktime(23, 59, 59, (int) date('m'), (int) date('d'), (int) date('Y'));

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
        $start = mktime(0, 0, 0, (int) date('m'), 1, (int) date('Y'));
        $end = mktime(23, 59, 59, (int) date('m'), (int) date('d'), (int) date('Y'));

        return $this->getQuery($start, $end);
    }

    private function getQuery(int $start, int $end): array
    {
        $dateStart = (new DateTimeImmutable())->setTimestamp($start);
        $dateEnd = (new DateTimeImmutable())->setTimestamp($end);

        $qb = $this->connection->createQueryBuilder();

        return $qb
            ->select(['SUM(i.quantity) AS qty', 'i.id'])
            ->from('restaurant_order_item', 'i')
//            ->innerJoin('i', 'restaurant_order', 'o',
//                $qb->expr()->and(
//                    $qb->expr()->comparison('o.paid_at', '>=', ':dateStart'),
//                    $qb->expr()->comparison('o.paid_at', '<=', ':dateEnd'),
//                    $qb->expr()->comparison('o.status', '>=', ':statusPaid'),
//                )
//            )
            ->innerJoin('i', 'restaurant_order', 'o', 'o.id = i.order_id')
            ->andWhere('o.paid_at >= :dateStart')
            ->andWhere('o.paid_at <= :dateEnd')
            ->andWhere('o.status = :statusPaid')
            ->setParameter('dateStart', $dateStart->format('Y-m-d H:i:s'))
            ->setParameter('dateEnd', $dateEnd->format('Y-m-d H:i:s'))
            ->setParameter('statusPaid', OrderStatus::STATUS_PAID)
            ->groupBy('i.id')
            ->orderBy('qty', 'DESC')
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
