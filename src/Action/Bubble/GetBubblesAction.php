<?php

namespace App\Action\Bubble;

use App\Domain\Bubble\Service\BubblesGetter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class GetBubblesAction
{
    private BubblesGetter $bubblesGetter;


    /**
     * The constructor.
     *
     * @param BubblesGetter $bubblesGetter equivalent to App\Domain\User\Service\UserFinder
     * //param Responder $responder The responder
     */
    public function __construct(BubblesGetter $bubblesGetter)
    {
        $this->bubblesGetter = $bubblesGetter;
    }

    /**
     * Action.   Similar to App\Action\User\UserFindAction
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     *
     * @return ResponseInterface The response
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        // Invoke the Domain with inputs and retain the result
        $result = $this->bubblesGetter->getBubbles();

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response
            ->withHeader('Access-Control-Allow-Origin', 'https://bcn.robnugen.com')
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}
