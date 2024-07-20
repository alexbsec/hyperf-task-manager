<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Http\Message\ServerRequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;

/**
 * @AutoController()
 */
class HttpController
{
    public function index(ServerRequestInterface $request, HttpResponse $response)
    {
        $queryParams = $request->getQueryParams();
        $body = $request->getParsedBody();
        $headers = $request->getHeaders();
        
        $data = [
            'query_params' => $queryParams,
            'body' => $body,
            'headers' => $headers,
        ];

        return $response->json($data);
    }
}
