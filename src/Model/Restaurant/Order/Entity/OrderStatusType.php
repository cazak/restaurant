<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class OrderStatusType extends StringType
{
    public const NAME = 'restaurant_order_status';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof OrderStatus ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?OrderStatus
    {
        return !empty($value) ? new OrderStatus((string) $value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
