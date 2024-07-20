<?php

namespace App\Controller;

use App\Model\User;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpMessage\Server\Response;
use Illuminate\Support\Str;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

/**
 * @AutoController()
 */
class AuthController
{
  public function register(RequestInterface $request, ValidatorFactoryInterface $validation)
  {
      $messages = [
        'email.unique' => 'Email already in use',
        'required' => 'The :attribute field is required',
        'string' => 'The :attribute field must be a string',
        'email' => 'The :attribute field must be a valid email address',
        'max' => 'The :attribute field must not exceed :max characters',
        'min' => 'The :attribute field must be at least :min characters',
      ];
      $validator = $validation->make(
          $request->all(),
          [
              'name' => 'required|string|max:255',
              'email' => 'required|string|email|max:255|unique:users',
              'password' => 'required|string|min:6',
          ],
          $messages,
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

  public function login(RequestInterface $request, ValidatorFactoryInterface $validation)
  {
    $validator = $validation->make(
      $request->all(),
      [
        'email' => 'required|string|email|max:255',
        'password' => 'required|string',
      ],
    );

    if ($validator->fails()) {
      $response = new Response();
      $response->getBody()->write(json_encode(['errors' => $validator->errors()]));
      return $response->withAddedHeader('Content-Type', 'application/json')->withStatus(422);
    }

    $user = User::where('email', $request->input('email'))->first();

    if (!$user || !password_verify($request->input('password'), $user->password)) {
      $response = new Response();
      $response->getBody()->write(json_encode(['errors' => 'Invalid credentials']));
      return $response->withAddedHeader('Content-Type', 'application/json')->withStatus(401);
    }

    $token = Str::random(60);
    $user->token = $token;
    $user->save();

    $response = new Response();
    $response->getBody()->write(json_encode(['bearer_token' => $token]));
    return $response->withAddedHeader('Content-Type', 'application/json')->withStatus(200);
  }
}

?>