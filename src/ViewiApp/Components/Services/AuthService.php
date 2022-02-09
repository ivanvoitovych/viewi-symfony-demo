<?php

namespace Components\Services;

use Components\Models\UserModel;
use Viewi\Common\ClientRouter;
use Viewi\Common\HttpClient;

class AuthService
{
    private HttpClient $http;
    private ClientRouter $router;
    public SessionState $session;
    private bool $initiated = false;
    private array $resolveQueue = [];
    public ?bool $isAuthenticated = null;
    public $user;

    public function __construct(HttpClient $httpClient, ClientRouter $clientRouter, SessionState $sessionState)
    {
        $this->http = $httpClient;
        $this->router = $clientRouter;
        $this->session = $sessionState;
    }

    public function getAuthentication(callable $callback)
    {
        $this->resolveQueue[] = $callback;
        if ($this->isAuthenticated === null) {
            if (!$this->initiated) {
                $this->initiated = true;
                $this->http->get('/api/auth/me')->then(function ($response) {
                    $this->isAuthenticated = !!$response['user'];
                    $this->user = $response['user'];
                    $this->resolveCallbacks();
                }, function () {
                    $this->isAuthenticated = false;
                    $this->resolveCallbacks();
                });
            }
        } else {
            $this->resolveCallbacks();
        }
    }

    public function resolveCallbacks()
    {
        while (count($this->resolveQueue) > 0) {
            $callBack = array_pop($this->resolveQueue);
            $callBack($this->isAuthenticated);
        }
    }

    public function reset()
    {
        $this->initiated = false;
        $this->isAuthenticated = null;
        $this->user = null;
    }

    public function logout(?callable $callback = null)
    {
        $this->http
            // ->with([$this->session, 'csrfTokenInterceptor'])
            ->post('/api/auth/logout')
            ->then(function ($response) use ($callback) {
                // echo $response;
                $this->reset();
                $this->router->navigate("/");
                if ($callback)
                    $callback($response);
            }, function ($error) use ($callback) {
                // echo $error;
                if ($callback)
                    $callback($error);
            });
    }
}
