<?php

namespace App\Action\Bubble;

use App\Domain\Bubble\Service\BubbleClicker;
use App\Domain\Bubble\Service\BubbleReader;
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
    public function __construct(Responder $responder, BubbleClicker $bubbleClicker, BubbleReader $bubbleReader)
    {
        $this->responder = $responder;
        $this->bubbleClicker = $bubbleClicker;
        $this->bubbleReader = $bubbleReader;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
      $version = (int)$args['version'];

      $bubble_data = (array)$request->getParsedBody();
      $bubble_object = json_decode($bubble_data["entry"]);

      $bubble_from_db = $this->bubbleReader->getBubbleData($bubble_object->bubble_id);
      $bubble_from_db->radius += 1;       // TODO make this increase based on bubble and click details
      ////  YAY THIS SAVES, TODO cleanup    Vars named "clicker" below are actually just updaters
      $this->bubbleClicker->clickBubble($bubble_object->bubble_id, (array)$bubble_from_db);
      // We must json encode the return_data https://www.slimframework.com/docs/v4/objects/response.html#returning-json
      $payload = json_encode($bubble_from_db);
      $response->getBody()->write($payload);
      return $response
                ->withHeader('Access-Control-Allow-Origin', 'https://bcn.robnugen.com')
                ->withHeader('Content-Type', 'application/json');
    }
}
