<?php

declare(strict_types=1);

namespace App\Http\Dish;

use App\Model\Infrastructure\Http\ParamFetcher;
use App\Model\Restaurant\Establishment\Command\CreateDish\CreateDish;
use App\Model\Restaurant\Establishment\Command\CreateDish\CreateDishCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dishes/create', methods: ['POST'])]
#[AsController]
final class CreateDishAction
{
    public function __construct(private readonly CreateDish $createDish)
    {
    }

    public function __invoke(Request $request): Response
    {
        $paramFetcher = ParamFetcher::fromRequestBody($request);

        $command = new CreateDishCommand(
            $paramFetcher->getRequiredString('name'),
            $paramFetcher->getRequiredFloat('price'),
        );

        $id = ($this->createDish)($command);

        return new JsonResponse(
            [
                'id' => $id,
            ],
            Response::HTTP_CREATED
        );
    }
}
