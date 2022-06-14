<?php


namespace woo_bookkeeping\App\Core;


abstract class ProductMapper
{

    const Map = [];

    static function ProductMap(array $product)
    {
        $result = [];

        foreach (static::Map as $key => $relation) {
            $result[$key] = $relation['callback'] ? call_user_func($relations['callback'], $product[$relation['field']]) : $product[$relation['field']];
        }

        return $result;
    }
}