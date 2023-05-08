<?php

namespace Farzai\KApi\Support;

class Str
{
    public static function camel($value)
    {
        return lcfirst(static::studly($value));
    }

    public static function studly($value)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return str_replace(' ', '', $value);
    }

    public static function snake($value, $delimiter = '_')
    {
        $key = $value;

        // Check if the given value is already in snake case.
        if (! ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', ucwords($value));

            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $value));

            // If value have special characters, replace it with the delimiter.
            $value = preg_replace_callback('/\W+/u', function ($matches) use ($delimiter) {
                return $delimiter;
            }, $value);
        }

        return $value;
    }

    public static function lower($value)
    {
        return mb_strtolower($value, 'UTF-8');
    }

    public static function replace($search, $replace, $subject)
    {
        return str_replace($search, $replace, $subject);
    }
}
