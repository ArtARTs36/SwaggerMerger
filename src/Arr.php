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
}
