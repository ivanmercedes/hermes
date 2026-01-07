# Hermes Router

Un enrutador HTTP rápido basado en atributos para PHP, inspirado en FastAPI.

[![Licencia: MIT](https://img.shields.io/badge/Licencia-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Versión PHP](https://img.shields.io/badge/PHP-%5E8.3-blue.svg)](https://php.net)

Español | [English](README.md)

## Características

- **Rápido y Ligero** - Optimizado para rendimiento con mínima sobrecarga
- **Enrutamiento Basado en Atributos** - Usa atributos de PHP 8 para definir rutas directamente en métodos de controladores
- **RESTful** - Soporte integrado para métodos HTTP GET, POST, PUT, DELETE
- **Parámetros de Ruta Dinámicos** - Extrae parámetros de URL con facilidad
- **Cero Configuración** - Funciona sin configuración adicional

## Requisitos

- PHP 8.3 o superior

## Instalación

Instalar vía Composer:

```bash
composer require ivanmercedes/hermes
```

## Inicio Rápido

### 1. Crear un Controlador

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

### 2. Inicializar el Enrutador

```php
<?php

require 'vendor/autoload.php';

use Hermes\Router\RouteCollection;
use Hermes\Router\Router;
use Hermes\Router\RouteCompiler;
use Hermes\Router\RouteMatcher;
use Hermes\Router\Exceptions\RouteNotFoundException;

// Inicializar enrutador
$collection = new RouteCollection();
$router = new Router($collection);
$router->registerController(UserController::class);

// Compilar rutas
$compiled = RouteCompiler::compile($collection);
$matcher = new RouteMatcher($compiled);

// Manejar petición
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

### 3. Ejecutar el Servidor

```bash
php -S localhost:8000 examples/server.php
```

Probar los endpoints:

```bash
# Obtener todos los usuarios
curl http://localhost:8000/users

# Obtener usuario por ID
curl http://localhost:8000/users/123

# Crear un usuario
curl -X POST http://localhost:8000/users
```

## Atributos de Métodos HTTP Disponibles

- `#[Get('/ruta')]` - Maneja peticiones GET
- `#[Post('/ruta')]` - Maneja peticiones POST
- `#[Put('/ruta')]` - Maneja peticiones PUT
- `#[Delete('/ruta')]` - Maneja peticiones DELETE

## Parámetros de Ruta

Extrae parámetros de URLs usando llaves:

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

## Estructura del Proyecto

```
src/
├── Attributes/       # Atributos de métodos HTTP (Get, Post, Put, Delete)
├── Contracts/        # Interfaces
└── Router/          # Componentes principales del enrutador
    ├── Route.php
    ├── RouteCollection.php
    ├── RouteCompiler.php
    ├── RouteMatcher.php
    └── Router.php
```

## Ejemplos

Revisa el directorio `examples/` para ver ejemplos completos y funcionales:

```bash
php -S localhost:8000 examples/server.php
```

## Licencia

Este proyecto está licenciado bajo la Licencia MIT - consulta el archivo [LICENSE](LICENSE.md) para más detalles.

## Contribuir

¡Las contribuciones son bienvenidas! No dudes en enviar un Pull Request.

## Hoja de Ruta

- [ ] Soporte para middlewares
- [ ] Grupos de rutas
- [ ] Nombres de rutas
- [ ] Generación de URLs
- [ ] Validación de peticiones
- [ ] Formateo de respuestas

---

Hecho con ❤️ por Ivan Mercedes
