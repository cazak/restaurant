<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http\Dish;

use App\Model\Restaurant\Establishment\Entity\Dish;
use App\Model\Restaurant\Establishment\Entity\DishRepository;
use App\Tests\Functional\ApiTestCase;
use App\Tests\Tools\DITools;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * @internal
 */
final class CreateDishActionTest extends ApiTestCase
{
    use DITools;

    private const URI = '/dishes/create';

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->getService(DishRepository::class);
    }

    public function test_success(): void
    {
        self::ensureKernelShutdown();
        $client = self::createClient();

        $client->request('POST', self::URI, [
            'name' => 'test_dish',
            'price' => 15.5,
        ]);

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        /** @var Dish $dish */
        $dish = $this->repo->get($responseData['id']);

        // assert
        $this->baseAssert($response, Response::HTTP_CREATED);
        self::assertEquals('id', array_keys($responseData)[0]);
        self::assertEquals($responseData['id'], $dish->getId()->getValue());
    }

    public function test_error(): void
    {
        self::ensureKernelShutdown();
        $client = self::createClient();
        $client->catchExceptions(false);

        $this->expectException(MethodNotAllowedHttpException::class);
        $client->request('GET', self::URI, [
            'name' => 'test_dish',
        ]);

        $this->assertEquals(405, $client->getResponse()->getStatusCode());
    }
}
