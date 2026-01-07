<?php

namespace Hermes\Attributes;

use Attribute;
use Hermes\Contracts\RouteAttribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Get implements RouteAttribute
{
    public function __construct(
        private string $path
    ) {
    }

    public function method(): string
    {
        return 'GET';
    }

    public function path(): string
    {
        return $this->path;
    }
}
