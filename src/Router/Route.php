<?php

namespace Hermes\Router;

use ReflectionParameter;

final class Route
{
    /**
     * @param ReflectionParameter[] $parameters
     */
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly string $controller,
        public readonly string $action,
        public readonly array $parameters
    ) {
    }
}
