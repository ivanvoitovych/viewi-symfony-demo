<?php

namespace Components\Views\Protected;

use Components\Guards\AuthGuard;
use Components\Models\PostModel;
use Viewi\BaseComponent;
use Viewi\Common\HttpClient;

class ProtectedPage extends BaseComponent
{
    public static array $_beforeStart = [AuthGuard::class];

    public string $title = 'Protected page';

    public ?PostModel $post = null;

    public function __init(HttpClient $http)
    {
        $http->get('/api')->then(function (PostModel $data) {
            $this->post = $data;
        }, function ($error) {
            echo $error;
        });
    }
}
