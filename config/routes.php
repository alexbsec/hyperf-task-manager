<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\HttpController@index');

Router::post('/register', 'App\Controller\AuthController@register');
Router::post('/login', 'App\Controller\AuthController@login');

Router::get('/favicon.ico', function () {
    return '';
});
