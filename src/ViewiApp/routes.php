<?php

use Components\Views\Home\HomePage;
use Components\Views\Login\LoginPage;
use Components\Views\NotFound\NotFoundPage;
use Components\Views\Pages\CounterPage;
use Components\Views\Pages\TodoAppPage;
use Components\Views\Protected\ProtectedPage;
use Viewi\Routing\Route as ViewiRoute;

ViewiRoute::get('/', HomePage::class);
ViewiRoute::get('/counter', CounterPage::class);
ViewiRoute::get('/counter/{page}', CounterPage::class);
ViewiRoute::get('/todo', TodoAppPage::class);

ViewiRoute::get('/login', LoginPage::class);
ViewiRoute::get('/protected', ProtectedPage::class);
ViewiRoute::get('*', NotFoundPage::class);
