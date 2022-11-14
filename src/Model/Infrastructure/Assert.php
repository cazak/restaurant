<?php

declare(strict_types=1);

namespace App\Model\Infrastructure;

use DateTimeImmutable;

final class Assert extends \Webmozart\Assert\Assert
{
    public static function dateTimeString(string $value, string $format, string $message = ''): void
    {
        $date = DateTimeImmutable::createFromFormat($format, $value);

        if (false === $date) {
            self::reportInvalidArgument(sprintf(
                '' === $message ? 'Date time string "%s" should be like "%s"' : $message,
                $value,
                $format
            ));
        }
    }
}
