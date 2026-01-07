<?php

namespace Hermes\Attributes;

use Attribute;
use Hermes\Contracts\RouteAttribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Post implements RouteAttribute
{
    public function __construct(
        private string $path
    ) {
    }

    public function method(): string
    {
        return 'POST';
    }

    public function path(): string
    {
        return $this->path;
    }
}
