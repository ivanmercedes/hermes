<?php

namespace Hermes\Router;

final class RouteCollection
{
    /** @var Route[] */
    private array $routes = [];

    public function add(Route $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * @return Route[]
     */
    public function all(): array
    {
        return $this->routes;
    }
}
