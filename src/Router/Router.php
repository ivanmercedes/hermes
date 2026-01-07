<?php

namespace Hermes\Router;

use ReflectionClass;
use ReflectionMethod;
use Hermes\Contracts\RouteAttribute;

final class Router
{
    public function __construct(
        private RouteCollection $routes
    ) {
    }

    public function registerController(string $controller): void
    {
        $reflection = new ReflectionClass($controller);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $attributes = $method->getAttributes(
                RouteAttribute::class,
                \ReflectionAttribute::IS_INSTANCEOF
            );

            foreach ($attributes as $attribute) {
                /** @var RouteAttribute $routeAttr */
                $routeAttr = $attribute->newInstance();

                $this->routes->add(
                    new Route(
                        method: $routeAttr->method(),
                        path: $routeAttr->path(),
                        controller: $controller,
                        action: $method->getName(),
                        parameters: $method->getParameters()
                    )
                );
            }
        }
    }
}
