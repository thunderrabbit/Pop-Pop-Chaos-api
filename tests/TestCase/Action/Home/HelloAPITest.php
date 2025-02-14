<?php

namespace App\Test\TestCase\Action\Home;

use App\Test\Traits\AppTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;

class HelloAPITest extends TestCase
{
    use AppTestTrait;

    /**
     * Test.
     *
     * @return void
     */
    public function testAction(): void
    {
        $request = $this->createRequest('GET', '/api/v1');
        $response = $this->app->handle($request);

        // Assert: Redirect
        $this->assertSame(StatusCodeInterface::STATUS_FOUND, $response->getStatusCode());
    }

    /**
     * Test invalid link.
     *
     * @return void
     */
    public function testPageNotFound(): void
    {
        $request = $this->createRequest('GET', '/api');
        $response = $this->app->handle($request);

        // Assert: Not found
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}
