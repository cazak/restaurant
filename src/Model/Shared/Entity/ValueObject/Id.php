<?php

declare(strict_types=1);

namespace App\Model\Shared\Entity\ValueObject;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

final class Id
{
    private readonly string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function next(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
