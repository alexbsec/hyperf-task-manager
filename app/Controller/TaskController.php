<?php 

namespace App\Controller;

use App\Model\Task;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpMessage\Server\Response;
use Illuminate\Support\Str;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;


/**
 * @AutoController()
 */
class TaskController
{
  public function index(RequestInterface $request)
  {
    $user = $request->getAttribute('user');
    $tasks = Task::where('user_id', $user->id)->get();

    $response = new Response();
    $response->getBody()->write(json_encode($tasks));
    return $response->withAddedHeader('Content-Type', 'application/json');
  }

  public function create(RequestInterface $request, ValidatorFactoryInterface $validation)
  {
    $user = $request->getAttribute('user');
    $validator = $validation->make(
      $request->all(),
      [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
      ],
    );

    if ($validator->fails()) {
      $response = new Response();
      $response->getBody()->write(json_encode(['errors' => $validator->errors()]));
      return $response->withAddedHeader('Content-Type', 'application/json')->withStatus(422);
    }

    $task = Task::create([
      'user_id' => $user->id,
      'title' => $request->input('title'),
      'description' => $request->input('description'),
    ]);


    $response = new Response();
    $response->getBody()->write(json_encode($task));
    return $response->withAddedHeader('Content-Type', 'application/json')->withStatus(201);
  }

  public function delete(RequestInterface $request, $id)
  {
    $user = $request->getAttribute('user');
    $task = Task::where('user_id', $user->id)->where('id', $id)->first();

    if (!$task) {
      $response = new Response();
      $response->getBody()->write(json_encode(['error' => 'Task not found']));
      return $response->withAddedHeader('Content-Type', 'application/json')->withStatus(404);
    }

    $task->delete();

    $response = new Response();
    $response->getBody()->write(json_encode(['message' => 'Task deleted successfully']));
    return $response->withAddedHeader('Content-Type', 'application/json');
  }

  public function update(RequestInterface $request, $id)
  {
    $user = $request->getAttribute('user');
    $task = Task::where('user_id', $user->id)->where('id', $id)->first();

    if (!$task) {
      $response = new Response();
      $response->getBody()->write(json_encode(['error' => 'Task not found']));
      return $response->withAddedHeader('Content-Type', 'application/json')->withStatus(404);
    }

    if (!$request->has('title') && !$request->has('description')) {
      $response = new Response();
      $response->getBody()->write(json_encode(['error' => 'You must provide at least one field to update']));
      return $response->withAddedHeader('Content-Type', 'application/json')->withStatus(400);
    }

    if ($request->has('title')) {
      $task->title = $request->input('title');
    }

    if ($request->has('description')) {
      $task->description = $request->input('description');
    }

    $task->save();

    $response = new Response();
    $response->getBody()->write(json_encode($task));
    return $response->withAddedHeader('Content-Type', 'application/json');
  }
}

?>