<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiTestCase extends WebTestCase
{
    protected function baseAssert(Response $response, int $statusCode): void
    {
        $this->assertResponseIsSuccessful();
        self::assertSame($statusCode, $response->getStatusCode());
        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));
        self::assertJson($response->getContent());
    }
}
