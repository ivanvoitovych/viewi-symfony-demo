<?php

namespace Components\Views\Common;

use Components\Services\AuthService;
use Viewi\BaseComponent;
use Viewi\Common\HttpClient;
use Viewi\DOM\Events\DOMEvent;

class MenuBar extends BaseComponent
{
    private HttpClient $http;
    public AuthService $auth;
    public string $message = '';

    public function __init(HttpClient $http, AuthService $auth)
    {
        $this->http = $http;
        $this->auth = $auth;
        $this->auth->getAuthentication(function () {
            // nothing
        });
    }

    public function logout(DOMEvent $event)
    {
        $event->preventDefault();
        $this->auth->logout(function ($message) {
            $this->message;
        });
    }
}
