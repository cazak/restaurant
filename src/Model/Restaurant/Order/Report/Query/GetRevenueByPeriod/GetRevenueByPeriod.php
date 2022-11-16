<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Report\Query\GetRevenueByPeriod;

use App\Model\Restaurant\Order\Entity\OrderStatus;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;

final class GetRevenueByPeriod
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function daily(): ?string
    {
        $start = mktime(0, 0, 0, (int)date('m'), (int)date('d'), (int)date('Y'));
        $end = mktime(23, 59, 59, (int)date('m'), (int)date('d'), (int)date('Y'));

        return $this->getQuery($start, $end);
    }

    public function weekly(): ?string
    {
        $start = mktime(0, 0, 0, (int) date('m'), date('d') - 6, (int) date('Y'));
        $end = mktime(23, 59, 59, (int) date('m'), (int) date('d'), (int) date('Y'));

        return $this->getQuery($start, $end);
    }

    public function monthly(): ?string
    {
        $start = mktime(0, 0, 0, (int)date('m'), 1, (int)date('Y'));
        $end = mktime(23, 59, 59, (int)date('m'), (int)date('d'), (int)date('Y'));

        return $this->getQuery($start, $end);
    }

    private function getQuery(int $start, int $end): ?string
    {
        $dateStart = (new DateTimeImmutable)->setTimestamp($start);
        $dateEnd = (new DateTimeImmutable)->setTimestamp($end);

        return $this->connection->createQueryBuilder()
            ->select(['SUM(o.price) AS revenue'])
            ->from('restaurant_order', 'o')
            ->where('o.paid_at >= :dateStart AND o.paid_at <= :dateEnd AND o.status = :statusPaid')
            ->setParameter('statusPaid', OrderStatus::STATUS_PAID)
            ->setParameter('dateStart', $dateStart->format('Y-m-d H:i:s'))
            ->setParameter('dateEnd', $dateEnd->format('Y-m-d H:i:s'))
            ->executeQuery()
            ->fetchOne();
    }
}
