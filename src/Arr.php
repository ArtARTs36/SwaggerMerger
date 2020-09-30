<?php

namespace ArtARTs36\SwaggerMerger;

class Arr
{
    public static function allocateIfKeyNotExists(array $arr, string ...$keys): array
    {
        foreach ($keys as $key) {
            if (!isset($arr[$key])) {
                $arr[$key] = [];
            }
        }

        return $arr;
    }

    public static function prepend(array $arr, string $prepend): array
    {
        foreach ($arr as &$tag) {
            $tag = $prepend . $tag;
        }

        return $arr;
    }

    public static function prependIfKeyExists(array $arr, string $key, string $prepend): array
    {
        if (! empty($arr[$key])) {
            $arr[$key] = $prepend . $arr[$key];
        }

        return $arr;
    }
}
