<?php

namespace Farzai\KApi\Entities;

use JsonSerializable;

abstract class AbstractEntity implements JsonSerializable
{
    protected $attributes = [];

    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function __isset(string $name)
    {
        return isset($this->attributes[$name]);
    }

    public function __unset(string $name)
    {
        unset($this->attributes[$name]);
    }

    public function jsonSerialize(): array
    {
        return $this->attributes;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}
