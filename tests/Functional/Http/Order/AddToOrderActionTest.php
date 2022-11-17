<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http\Order;

use App\Model\Restaurant\Establishment\Entity\Dish;
use App\Model\Restaurant\Order\Entity\Order;
use App\Model\Restaurant\Order\Entity\OrderRepository;
use App\Model\Restaurant\Order\Entity\OrderStatus;
use App\Model\Shared\Entity\ValueObject\Id;
use App\Tests\Functional\ApiTestCase;
use App\Tests\Resource\Fixture\Order\DishForOrderFixture;
use App\Tests\Tools\DITools;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Component\HttpFoundation\Response;

final class AddToOrderActionTest extends ApiTestCase
{
    use DITools;

    private const URI = '/order/add';
    private AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->getService(OrderRepository::class);
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_success_create(): void
    {
        $referenceRepository = $this->databaseTool->loadFixtures([DishForOrderFixture::class])->getReferenceRepository();

        /** @var Dish $dish */
        $dish = $referenceRepository->getReference(DishForOrderFixture::REFERENCE);

        self::ensureKernelShutdown();
        $client = self::createClient();

        $client->request('POST', self::URI, [
            'customerId' => Id::next(),
            'dishId' => $dish->getId()->getValue(),
        ]);

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        /** @var Order $order */
        $order = $this->repo->get($responseData['orderId']);

        // assert
        $this->baseAssert($response, Response::HTTP_OK);
        $this->assertEquals($order->getId()->getValue(), $responseData['orderId']);
        $this->assertEquals($order->getPrice(), $responseData['price']);
        $this->assertEquals(OrderStatus::STATUS_NEW, $responseData['status']);
    }

    public function test_success_create_and_add(): void
    {
        $referenceRepository = $this->databaseTool->loadFixtures([DishForOrderFixture::class])->getReferenceRepository();

        /** @var Dish $dish */
        $dish = $referenceRepository->getReference(DishForOrderFixture::REFERENCE);

        self::ensureKernelShutdown();
        $client = self::createClient();

        $customerId = Id::next();
        $client->request('POST', self::URI, [
            'customerId' => $customerId,
            'dishId' => $dish->getId()->getValue(),
        ]);
        $client->request('POST', self::URI, [
            'customerId' => $customerId,
            'dishId' => $dish->getId()->getValue(),
        ]);

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        /** @var Order $order */
        $order = $this->repo->get($responseData['orderId']);

        // assert
        $this->baseAssert($response, Response::HTTP_OK);
        $this->assertEquals($order->getId()->getValue(), $responseData['orderId']);
        $this->assertEquals($order->getPrice(), $responseData['price']);
        $this->assertEquals(DishForOrderFixture::PRICE * 2, (float) $responseData['price']);
        $this->assertEquals(OrderStatus::STATUS_NEW, $responseData['status']);
    }
}
