<?php

namespace App\Controller;

use App\Model\User;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpMessage\Server\Response;
use Hyperf\Utils\Str;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

/**
 * @AutoController()
 */
class AuthController
{
  public function register(RequestInterface $request, ValidatorFactoryInterface $validation)
  {
      $validator = $validation->make(
          $request->all(),
          [
              'name' => 'required|string|max:255',
              'email' => 'required|string|email|max:255|unique:users',
              'password' => 'required|string|min:6',
          ]
      );

      if ($validator->fails()) {
          $response = new Response();
          $response->getBody()->write(json_encode(['errors' => $validator->errors()]));
          return $response->withAddedHeader('Content-Type', 'application/json')->withStatus(422);
      }

      $user = User::create([
          'name' => $request->input('name'),
          'email' => $request->input('email'),
          'password' => password_hash($request->input('password'), PASSWORD_BCRYPT),
      ]);

      $response = new Response();
      $response->getBody()->write(json_encode($user));
      return $response->withAddedHeader('Content-Type', 'application/json')->withStatus(201);
  }
}

?>