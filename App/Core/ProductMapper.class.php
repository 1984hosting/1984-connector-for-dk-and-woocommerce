<?php

namespace woo_bookkeeping\App\Core;

abstract class ProductMapper
{
    const Map = [];

    static function ProductMap(array $product): array
    {
        $result = [];
        foreach (static::Map as $key => $relation) {
            $result[$relation['field']] = !empty($relation['callback']) ? call_user_func($relation['callback'], $product[$key] ?? '') : ($product[$key] ?? '');
        }

        return $result;
    }

    public static function setBoolTrue(): float
    {
        return true;
    }

    public static function toFloat($price): float
    {
        return number_format((float)$price, 2, '.', '');
    }
}