<?php

namespace Hermes\Router;
use Hermes\Router\Exceptions\RouteNotFoundException;
final class RouteMatcher
{
    public function __construct(
        private array $compiledRoutes
    ) {
    }

    public function match(string $method, string $uri): array
    {
        foreach ($this->compiledRoutes as $entry) {
            if ($entry['method'] !== strtoupper($method)) {
                continue;
            }

            if (preg_match($entry['regex'], $uri, $matches)) {
                return [
                    'route' => $entry['route'],
                    'params' => array_filter(
                        $matches,
                        'is_string',
                        ARRAY_FILTER_USE_KEY
                    )
                ];
            }
        }

        throw new RouteNotFoundException(
            sprintf('Route not found: %s %s', $method, $uri)
        );
    }
}
