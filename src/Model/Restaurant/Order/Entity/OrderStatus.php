<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Entity;

use Webmozart\Assert\Assert;

final class OrderStatus
{
    public const STATUS_NEW = 'new';
    public const STATUS_PAID = 'paid';

    private readonly string $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, [
            self::STATUS_NEW,
            self::STATUS_PAID,
        ]);
        $this->value = $value;
    }

    public static function new(): self
    {
        return new self(self::STATUS_NEW);
    }

    public static function paid(): self
    {
        return new self(self::STATUS_PAID);
    }

    public function isNew(): bool
    {
        return self::STATUS_NEW === $this->value;
    }

    public function isPaid(): bool
    {
        return self::STATUS_PAID === $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
