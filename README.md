# Hermes Router

A fast, attribute-based HTTP router for PHP inspired by FastAPI.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%5E8.3-blue.svg)](https://php.net)

[Español](README.es.md) | English

## Features

- **Fast & Lightweight** - Optimized for performance with minimal overhead
- **Attribute-Based Routing** - Use PHP 8 attributes to define routes directly on controller methods
- **RESTful** - Built-in support for GET, POST, PUT, DELETE HTTP methods
- **Dynamic Route Parameters** - Extract URL parameters with ease
- **Zero Configuration** - Works out of the box

## Requirements

- PHP 8.3 or higher

## Installation

Install via Composer:

```bash
composer require ivanmercedes/hermes
```

## Quick Start

### 1. Create a Controller

```php
<?php

use Hermes\Attributes\Get;
use Hermes\Attributes\Post;

class UserController
{
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
```

### 2. Bootstrap the Router

```php
<?php

require 'vendor/autoload.php';

use Hermes\Router\RouteCollection;
use Hermes\Router\Router;
use Hermes\Router\RouteCompiler;
use Hermes\Router\RouteMatcher;
use Hermes\Router\Exceptions\RouteNotFoundException;

// Initialize router
$collection = new RouteCollection();
$router = new Router($collection);
$router->registerController(UserController::class);

// Compile routes
$compiled = RouteCompiler::compile($collection);
$matcher = new RouteMatcher($compiled);

// Handle request
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

try {
    $match = $matcher->match($method, $uri);
    
    $route = $match['route'];
    $params = $match['params'];
    
    $controller = new ($route->controller)();
    $response = $controller->{$route->action}(...array_values($params));
    
    echo json_encode($response);
} catch (RouteNotFoundException $e) {
    http_response_code(404);
    echo json_encode(['error' => $e->getMessage()]);
}
```

### 3. Run the Server

```bash
php -S localhost:8000 examples/server.php
```

Test your endpoints:

```bash
# Get all users
curl http://localhost:8000/users

# Get user by ID
curl http://localhost:8000/users/123

# Create a user
curl -X POST http://localhost:8000/users
```

## Available HTTP Method Attributes

- `#[Get('/path')]` - Handle GET requests
- `#[Post('/path')]` - Handle POST requests
- `#[Put('/path')]` - Handle PUT requests
- `#[Delete('/path')]` - Handle DELETE requests

## Route Parameters

Extract parameters from URLs using curly braces:

```php
#[Get('/posts/{id}/comments/{commentId}')]
public function showComment(int $id, int $commentId): array
{
    return [
        'post_id' => $id,
        'comment_id' => $commentId
    ];
}
```

## Project Structure

```
src/
├── Attributes/       # HTTP method attributes (Get, Post, Put, Delete)
├── Contracts/        # Interfaces
└── Router/          # Core routing components
    ├── Route.php
    ├── RouteCollection.php
    ├── RouteCompiler.php
    ├── RouteMatcher.php
    └── Router.php
```

## Examples

Check the `examples/` directory for complete working examples:

```bash
php -S localhost:8000 examples/server.php
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE.md) file for details.


## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Roadmap

- [ ] Middleware support
- [ ] Route groups
- [ ] Route naming
- [ ] URL generation
- [ ] Request validation
- [ ] Response formatting

---

Made with ❤️ by Ivan Mercedes
