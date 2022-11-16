<?php

declare(strict_types=1);

namespace App\Http\Report\Top\ByQuantity;

use App\Model\Restaurant\Order\Report\Query\TopDishesInPeriodByQuantity\TopDishesInPeriodByQuantity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reports/top/quantity/monthly', methods: ['GET'])]
#[AsController]
final class MonthlyTopByQuantity
{
    public function __construct(private readonly TopDishesInPeriodByQuantity $topDishesInPeriodByQuantity)
    {
    }

    public function __invoke(): Response
    {
        return new JsonResponse($this->topDishesInPeriodByQuantity->monthly(), Response::HTTP_OK);
    }
}
