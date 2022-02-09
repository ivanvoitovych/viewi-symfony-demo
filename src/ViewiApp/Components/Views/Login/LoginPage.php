<?php

namespace Components\Views\Login;

use Components\Services\AuthService;
use Viewi\BaseComponent;
use Viewi\Common\ClientRouter;
use Viewi\Common\HttpClient;
use Viewi\DOM\Events\DOMEvent;

class LoginPage extends BaseComponent
{
    public string $title = 'Login';
    private HttpClient $http;
    private AuthService $auth;
    private ClientRouter $router;
    public string $username = '';
    public string $password = '';
    public string $message = '';

    public function __init(HttpClient $http, AuthService $auth, ClientRouter $router)
    {
        $this->http = $http;
        $this->auth = $auth;
        $this->router = $router;
    }

    public function login(DOMEvent $event)
    {
        $event->preventDefault();
        $this->message = 'Authorizing..';
        $this->http
            ->post('/api/auth/login', [
                'username' => $this->username,
                'password' => $this->password
            ])
            ->then(function ($response) {
                $this->message = 'You are logged in successfully!';
                $this->auth->reset();
                $this->auth->getAuthentication(function (?bool $isAuthenticated) {
                    if ($isAuthenticated)
                        $this->router->navigate('/protected');
                });
            }, function ($error) {
                $this->message = $error;
            });
    }
}
