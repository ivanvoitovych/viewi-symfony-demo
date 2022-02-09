<?php

namespace Components\Guards;

use Components\Services\AuthService;
use Viewi\Common\ClientRouter;
use Viewi\Components\Interfaces\IMiddleware;

class AuthGuard implements IMiddleware
{
    private ClientRouter $router;
    private AuthService $authService;

    public function __construct(ClientRouter $clientRouter, AuthService $authenticationService)
    {
        $this->router = $clientRouter;
        $this->authService = $authenticationService;
    }

    public function run(callable $next)
    {
        $this->authService->getAuthentication(function ($isAuthenticated) use ($next) {
            if ($isAuthenticated) {
                $next();
            } else {
                $this->router->navigate("/login");
            }
        });
    }
}
