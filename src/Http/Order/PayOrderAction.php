<?php

declare(strict_types=1);

namespace App\Http\Order;

use App\Model\Infrastructure\Http\ParamFetcher;
use App\Model\Restaurant\Order\Command\Pay\OrderPay;
use App\Model\Restaurant\Order\Command\Pay\OrderPayCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order/pay', methods: ['POST'])]
#[AsController]
final class PayOrderAction
{
    public function __construct(private readonly OrderPay $orderPay)
    {
    }

    public function __invoke(Request $request): Response
    {
        $query = ParamFetcher::fromRequestBody($request);

        $orderPayCommand = new OrderPayCommand(
            $query->getRequiredString('customerId'),
            $query->getRequiredString('orderId'),
        );

        ($this->orderPay)($orderPayCommand);

        return new JsonResponse(['success' => true], Response::HTTP_OK);
    }
}
