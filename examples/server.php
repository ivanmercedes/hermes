<?php

/**
 * Run with:
 * php -S localhost:8000 examples/server.php
 */

require __DIR__ . '/../vendor/autoload.php';

use Hermes\Router\RouteCollection;
use Hermes\Router\Router;
use Hermes\Router\RouteCompiler;
use Hermes\Router\RouteMatcher;
use Hermes\Attributes\Get;
use Hermes\Attributes\Post;
use Hermes\Router\Exceptions\RouteNotFoundException;

/*
|--------------------------------------------------------------------------
| Example Controller
|--------------------------------------------------------------------------
*/

final class UserController
{
    #[Get('/')]
    public function welcome(): array
    {
        return [
            'message' => 'Lucent Router is running!',
            'endpoints' => [
                'GET /users' => 'List all users',
                'GET /users/{id}' => 'Get user by ID',
                'POST /users' => 'Create a new user',
            ]
        ];
    }

    #[Get('/users')]
    public function index(): array
    {
        return ['users' => []];
    }

    #[Get('/users/{id}')]
    public function show(int $id): array
    {
        return ['id' => $id];
    }

    #[Post('/users')]
    public function store(): array
    {
        return ['created' => true];
    }
}

/*
|--------------------------------------------------------------------------
| Bootstrap Router
|--------------------------------------------------------------------------
*/

$collection = new RouteCollection();
$router = new Router($collection);
$router->registerController(UserController::class);

$compiled = RouteCompiler::compile($collection);
$matcher = new RouteMatcher($compiled);

/*
|--------------------------------------------------------------------------
| Handle HTTP Request
|--------------------------------------------------------------------------
*/

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

header('Content-Type: application/json');

try {
    $match = $matcher->match($method, $uri);

    $route = $match['route'];
    $params = $match['params'];

    $controller = new ($route->controller)();

    $response = $controller->{$route->action}(
        ...array_values($params)
    );

    echo json_encode($response, JSON_PRETTY_PRINT);
} catch (RouteNotFoundException $e) {
    http_response_code(404);
    echo json_encode(['error' => $e->getMessage()]);
}
