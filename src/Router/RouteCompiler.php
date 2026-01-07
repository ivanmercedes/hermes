<?php

namespace Hermes\Router;

final class RouteCompiler
{
    public static function compile(RouteCollection $collection): array
    {
        $compiled = [];

        foreach ($collection->all() as $route) {
            $pattern = preg_replace(
                '#\{(\w+)\}#',
                '(?P<$1>[^/]+)',
                $route->path
            );

            $compiled[] = [
                'method' => $route->method,
                'regex' => '#^' . $pattern . '$#',
                'route' => $route,
            ];
        }

        return $compiled;
    }
}
