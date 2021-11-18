<?php

// Define app routes

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Tuupola\Middleware\HttpBasicAuthentication;

return function (App $app) {
    // Redirect to Swagger documentation
    $app->get('/', \App\Action\Home\HomeAction::class)->setName('home');

    // Swagger API documentation
    $app->get('/docs/v1', \App\Action\OpenApi\Version1DocAction::class)->setName('docs');

    // Password protected area
    $app->group(
        '/api/{version}',
        function (RouteCollectorProxy $app) {
            $app->get('', \App\Action\Home\HelloAPI::class)->setName('API home');
            $app->get('/', \App\Action\Home\HelloAPI::class)->setName('API home');
            $app->get('/get_bubbles/', \App\Action\Bubble\GetBubblesAction::class)->setName('what is this name anyway');
            $app->post('/click_bubble', \App\Action\Bubble\ClickBubbleAction::class);
            $app->get('/users', \App\Action\User\UserFindAction::class);
            $app->post('/users', \App\Action\User\UserCreateAction::class);
            $app->get('/users/{user_id}', \App\Action\User\UserReadAction::class);
            $app->put('/users/{user_id}', \App\Action\User\UserUpdateAction::class);
            $app->delete('/users/{user_id}', \App\Action\User\UserDeleteAction::class);
        }
    ); //->add(HttpBasicAuthentication::class);   // 2021 Nov 7 this was causing errors I don't know how to correct yet
};
