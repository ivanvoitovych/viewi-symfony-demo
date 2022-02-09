<?php

use App\Controller\ApiController;
use App\Controller\AuthController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('api_auth_login', '/api/auth/login')
        ->controller([AuthController::class, 'login'])
        ->methods(['POST']);

    $routes->add('api_auth_logout', '/api/auth/logout')
        ->controller([AuthController::class, 'logout'])
        ->methods(['POST']);

    $routes->add('api_auth_me', '/api/auth/me')
        ->controller([AuthController::class, 'me'])
        ->methods(['GET']);

    $routes->add('api', '/api')
        ->controller([ApiController::class, 'data']);
};
