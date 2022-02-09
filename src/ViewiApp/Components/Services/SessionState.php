<?php

namespace Components\Services;

use Viewi\Common\HttpClient;
use Viewi\Common\HttpHandler;

class SessionState
{
    private HttpClient $http;
    private bool $initiated = false;
    private ?string $CSRFToken = null;
    private array $resolveQueue = [];

    public function __construct(HttpClient $httpClient)
    {
        $this->http = $httpClient;
    }

    public function csrfTokenInterceptor(HttpHandler $handler)
    {
        if ($this->CSRFToken !== null) {
            $handler->httpClient->setOptions([
                'headers' => [
                    'X-CSRF-TOKEN' => $this->CSRFToken
                ]
            ]);
            $handler->handle(function ($next) {
                $next();
            });
        }
        if (!$this->initiated) {
            $this->initiated = true;
            $this->http->post('/api/authorization/session')->then(function ($response) use ($handler) {
                $this->CSRFToken = $response['data']['CSRFToken'];
                $handler->httpClient->setOptions([
                    'headers' => [
                        'X-CSRF-TOKEN' => $this->CSRFToken
                    ]
                ]);
                $handler->handle(function ($next) {
                    $next();
                });
            }, function ($error) use ($handler) {
                $handler->handle(function ($next) use ($handler, $error) {
                    $handler->response->success = false;
                    $handler->response->content = $error;
                    $next();
                });
            });
        }
    }

    public function resolveCallbacks()
    {
        foreach ($this->resolveQueue as $callBack) {
            $callBack($this->CSRFToken);
        }
        $this->resolveQueue = [];
    }
}
