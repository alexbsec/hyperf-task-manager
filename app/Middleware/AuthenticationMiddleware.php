<?php 

namespace App\Middleware;

use App\Model\User;
use Hyperf\HttpMessage\Server\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Hyperf\Di\Annotation\Inject;

class AuthenticationMiddleware implements MiddlewareInterface
{
  /**
   * @Inject
   * @var ContainerInterface
   */

   protected $container;

   public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
   {
      $authHeader = $request->getHeaderLine('Authorization');
      if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $response = new Response();
        $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
        return $response->withAddedHeader('Content-Type', 'application/json')->withStatus(401);
      }

      $token = $matches[1];
      $user = User::where('token', $token)->first();

      if (!$user) {
        $response = new Response();
        $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
        return $response->withAddedHeader('Content-Type', 'application/json')->withStatus(401);
      }

      return $handler->handle($request->withAttribute('user', $user));

   }
}

?>