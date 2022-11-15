<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Command\Pay;

use Exception;

final class WrongBuyerException extends Exception
{
}
