<?php

namespace Hermes\Contracts;

interface RouteAttribute
{
    public function method(): string;
    public function path(): string;
}