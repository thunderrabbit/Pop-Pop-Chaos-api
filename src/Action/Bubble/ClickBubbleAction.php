<?php

namespace App\Action\Bubble;

use App\Domain\Bubble\Service\BubbleClicker;
use App\Responder\Responder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ClickBubbleAction
{
    private Responder $responder;

    private BubbleClicker $bubbleClicker;

    /**
     * The constructor.
     *
     * @param Responder $responder The responder
     * @param BubbleClicker $bubbleClicker The service
     */
    public function __construct(Responder $responder, BubbleClicker $bubbleClicker)
    {
        $this->responder = $responder;
        $this->bubbleClicker = $bubbleClicker;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
      $version = (int)$args['version'];

      $bubble_data = (array)$request->getParsedBody();
      $bubble_array = json_decode($bubble_data["entry"]);
      $bubble_array->radius = $bubble_array->radius + 1;   // MUST check DB otherwise can be hacked from frontend

      ////   FOR NOW JUST TESTING IF THIS SAVES, bUT WE STILL MUST READ FROM DB TO SEEW AHT SIZE TO MAKE RADIUS
      $this->bubbleClicker->clickBubble($bubble_array->bubble_id, (array)$bubble_array);
      // We must json encode the return_data https://www.slimframework.com/docs/v4/objects/response.html#returning-json
      $payload = json_encode($bubble_array);
      $response->getBody()->write($payload);
      return $response
                ->withHeader('Access-Control-Allow-Origin', 'https://bcn.robnugen.com')
                ->withHeader('Content-Type', 'application/json');
    }
}
