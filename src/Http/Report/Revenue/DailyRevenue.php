<?php

declare(strict_types=1);

namespace App\Http\Report\Revenue;

use App\Model\Restaurant\Order\Report\Query\GetRevenueByPeriod\GetRevenueByPeriod;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reports/revenue/daily', methods: ['GET'])]
#[AsController]
final class DailyRevenue
{
    public function __construct(private readonly GetRevenueByPeriod $getRevenueByPeriod)
    {
    }

    public function __invoke(): Response
    {
        return new JsonResponse($this->getRevenueByPeriod->daily(), Response::HTTP_OK);
    }
}
