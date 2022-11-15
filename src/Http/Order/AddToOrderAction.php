<?php

declare(strict_types=1);

namespace App\Http\Order;

use App\Model\Infrastructure\Http\ParamFetcher;
use App\Model\Restaurant\Order\Command\AddToOrder\AddToOrder;
use App\Model\Restaurant\Order\Command\AddToOrder\AddToOrderCommand;
use App\Model\Restaurant\Order\Query\GetOrder\GetOrder;
use App\Model\Restaurant\Order\Query\GetOrder\GetOrderQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order/add', methods: ['POST'])]
#[AsController]
final class AddToOrderAction
{
    public function __construct(private readonly AddToOrder $addToOrder, private readonly GetOrder $getOrder)
    {
    }

    public function __invoke(Request $request): Response
    {
        $query = ParamFetcher::fromRequestBody($request);

        $command = new AddToOrderCommand(
            $query->getRequiredString('dishId'),
            $query->getRequiredString('customerId'),
        );

        $id = ($this->addToOrder)($command);

        $order = ($this->getOrder)(new GetOrderQuery($id));

        return new JsonResponse($order, Response::HTTP_OK);
    }
}
