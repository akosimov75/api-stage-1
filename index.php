<?php
require __DIR__ . '/vendor/autoload.php';
use App\Api;
use App\Controllers\TodoController;
use App\Repositories\TodoRepository;
use App\Storage\JsonStorage;
use App\HttpMethods;
use App\Exceptions\NotFoundException;
use App\JsonResponse;


$todoStorage = new JsonStorage('data.json');
$todoRepository = new TodoRepository($todoStorage);
$todoController = new TodoController($todoRepository);

$api = new Api('api/v1');

$api->add_route(HttpMethods::GET, '/todos/:id', [$todoController, 'readTodo']);
$api->add_route(HttpMethods::GET, '/todos', [$todoController, 'listTodo']);
$api->add_route(HttpMethods::POST, '/todos', [$todoController, 'createTodo']);
$api->add_route(HttpMethods::PUT, '/todos/:id', [$todoController, 'editTodo']);
$api->add_route(HttpMethods::DELETE, '/todos/:id', [$todoController, 'deleteTodo']);



try {
    $api->run();
} catch (NotFoundException $e) {
    JsonResponse::notFound();
}
