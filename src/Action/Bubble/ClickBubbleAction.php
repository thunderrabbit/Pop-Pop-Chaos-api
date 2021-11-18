<?php

namespace App\Action\Bubble;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ClickBubbleAction
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ) {
      $version = $args['version'];
      // We must json encode the return_data https://www.slimframework.com/docs/v4/objects/response.html#returning-json
      $return_data = array();
      $bubble_data = $request->getParsedBody();
      $bubble_array = json_decode($bubble_data["entry"]);
      $bubble_array->radius = $bubble_array->radius + 1;   // MUST check DB otherwise can be hacked from frontend
      $payload = json_encode($bubble_array);
      $response->getBody()->write($payload);
      return $response
                ->withHeader('Access-Control-Allow-Origin', 'https://bcn.robnugen.com')
                ->withHeader('Content-Type', 'application/json');
    }
}
