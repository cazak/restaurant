<?php

declare(strict_types=1);

namespace App\Http\Dish;

use App\Model\Infrastructure\Http\ParamFetcher;
use App\Model\Restaurant\Establishment\Query\GetDishes\GetDishes;
use App\Model\Restaurant\Establishment\Query\GetDishes\GetDishesQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dishes', methods: ['GET'])]
#[AsController]
final class GetDishesAction
{
    public function __construct(private readonly GetDishes $getDishes)
    {
    }

    public function __invoke(Request $request): Response
    {
        $query = ParamFetcher::fromRequestQuery($request);

        $getDishesQuery = new GetDishesQuery(
            $query->getNullableString('sort'),
            $query->getNullableString('order'),
            $query->getNullableInt('size'),
            $query->getNullableInt('page'),
        );

        $getDishes = ($this->getDishes)($getDishesQuery);

        return new JsonResponse($getDishes, Response::HTTP_OK);
    }
}
