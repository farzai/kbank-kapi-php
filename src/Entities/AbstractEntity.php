<?php

namespace Farzai\KApi\Entities;

use Farzai\KApi\Support\Str;
use JsonSerializable;

abstract class AbstractEntity implements JsonSerializable
{
    protected $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function __get(string $name)
    {
        if (method_exists($this, $method = 'get'.ucfirst(Str::camel($name)).'Attribute')) {
            return $this->{$method}($this->attributes[$name] ?? null);
        }

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

    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }

        return $this;
    }
}
