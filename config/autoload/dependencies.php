<?php

declare(strict_types=1);

use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpMessage\Server\Response;

return [
    ResponseInterface::class => Response::class,
    // other dependencies...
];
