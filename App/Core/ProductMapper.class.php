<?php

namespace woo_bookkeeping\App\Core;

abstract class ProductMapper
{
    const Map = [];

    /**
     * Converting fields to common format
     * @param array $fields
     * @return array
     */
    static function ProductMap(array $product): array
    {
        $result = [];
        foreach (static::Map as $key => $relation) {
            $result[$relation['field']] = !empty($relation['callback']) ? call_user_func($relation['callback'], $product[$key] ?? '') : ($product[$key] ?? '');
        }

        return $result;
    }

    /**
     * Converting fields to common format
     * @param array $fields
     * @return array
     */
    static function ProductMapReverse(array $fields): array
    {
        $fields_map = array_keys(static::Map);

        foreach ($fields as &$field) {
            $key = array_search($field, array_column(static::Map, 'field'));
            $field = $fields_map[$key];
        }

        return $fields;
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