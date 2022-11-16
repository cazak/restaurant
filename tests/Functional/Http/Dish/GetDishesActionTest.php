<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http\Dish;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Resource\Fixture\Dish\DishFixture;
use App\Tests\Tools\DITools;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
final class GetDishesActionTest extends ApiTestCase
{
    use DITools;

    private AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_success(): void
    {
        $this->databaseTool->loadFixtures([DishFixture::class]);

        self::ensureKernelShutdown();
        $client = self::createClient();

        $client->request('GET', '/dishes');

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // assert
        $this->baseAssert($response, Response::HTTP_OK);
        self::assertEquals(20, $responseData['itemCount']);
        self::assertEquals(2, $responseData['pageCount']);
        self::assertEquals(10, count($responseData['items']));
        self::assertEquals('name', array_keys($responseData['items'][0])[0]);
        self::assertEquals('price', array_keys($responseData['items'][0])[1]);
    }
}
