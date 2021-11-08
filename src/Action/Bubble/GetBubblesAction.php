<?php

namespace App\Action\Bubbles;

use App\Domain\Bubble\Service\BubblesGetter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class GetBubblesAction
{
    private $bubblesGetter;

    public function __construct(BubblesGetter $bubblesGetter)
    {
        $this->bubblesGetter = $bubblesGetter;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        // Invoke the Domain with inputs and retain the result
        $result = $this->bubblesGetter->getBubbles();

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}
