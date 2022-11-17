<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http\Order;

use App\Model\Restaurant\Order\Command\Pay\WrongBuyerException;
use App\Model\Restaurant\Order\Entity\Order;
use App\Model\Restaurant\Order\Entity\OrderAlreadyPaidException;
use App\Model\Restaurant\Order\Entity\OrderRepository;
use App\Model\Restaurant\Order\Entity\OrderStatus;
use App\Model\Shared\Entity\ValueObject\Id;
use App\Tests\Functional\ApiTestCase;
use App\Tests\Resource\Fixture\Order\OrderFixture;
use App\Tests\Tools\DITools;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
final class PayOrderActionTest extends ApiTestCase
{
    use DITools;

    private const URI = '/order/pay';
    private AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->getService(OrderRepository::class);
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_success(): void
    {
        $referenceRepository = $this->databaseTool->loadFixtures([OrderFixture::class])->getReferenceRepository();
        /** @var Order $order */
        $order = $referenceRepository->getReference(OrderFixture::REFERENCE);

        self::assertEquals(OrderStatus::STATUS_NEW, $order->getStatus()->getValue());

        self::ensureKernelShutdown();
        $client = self::createClient();

        $client->request('POST', self::URI, [
            'customerId' => $order->getCustomerId()->getValue(),
            'orderId' => $order->getId()->getValue(),
        ]);

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->baseAssert($response, Response::HTTP_OK);
        self::assertEquals(OrderStatus::STATUS_NEW, $order->getStatus()->getValue());
    }

    public function test_error_buyer(): void
    {
        $referenceRepository = $this->databaseTool->loadFixtures([OrderFixture::class])->getReferenceRepository();
        /** @var Order $order */
        $order = $referenceRepository->getReference(OrderFixture::REFERENCE);

        self::assertEquals(OrderStatus::STATUS_NEW, $order->getStatus()->getValue());

        self::ensureKernelShutdown();
        $client = self::createClient();

        $this->expectException(WrongBuyerException::class);

        $client->catchExceptions(false);
        $client->request('POST', self::URI, [
            'customerId' => Id::next(), // random id
            'orderId' => $order->getId()->getValue(),
        ]);
    }

    public function test_already_paid(): void
    {
        $referenceRepository = $this->databaseTool->loadFixtures([OrderFixture::class])->getReferenceRepository();
        /** @var Order $order */
        $order = $referenceRepository->getReference(OrderFixture::REFERENCE);

        self::assertEquals(OrderStatus::STATUS_NEW, $order->getStatus()->getValue());

        self::ensureKernelShutdown();
        $client = self::createClient();

        $this->expectException(OrderAlreadyPaidException::class);

        $client->catchExceptions(false);
        $client->request('POST', self::URI, [
            'customerId' => $order->getCustomerId()->getValue(),
            'orderId' => $order->getId()->getValue(),
        ]);
        $client->request('POST', self::URI, [
            'customerId' => $order->getCustomerId()->getValue(),
            'orderId' => $order->getId()->getValue(),
        ]);
    }
}
