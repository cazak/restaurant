<?php

declare(strict_types=1);

namespace App\Http\Report\Top\ByRevenue;

use App\Model\Restaurant\Order\Report\Query\TopDishesInPeriodByRevenue\TopDishesInPeriodByRevenue;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reports/top/revenue/daily', methods: ['GET'])]
#[AsController]
final class DailyTopByRevenue
{
    public function __construct(private readonly TopDishesInPeriodByRevenue $topDishesInPeriodByRevenue)
    {
    }

    public function __invoke(): Response
    {
        return new JsonResponse($this->topDishesInPeriodByRevenue->daily(), Response::HTTP_OK);
    }
}
