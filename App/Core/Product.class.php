<?php

namespace woo_bookkeeping\App\Core;

use woo_bookkeeping\App\Modules\dkPlus\ProductMap;

abstract class Product extends Woo_Query
{
    //abstract protected static function productSyncOne(array $needed_fields, $product_id);

    //abstract protected static function productSyncAll(array $needed_fields);

    /**
     * Search for a product in the resulting array (from remote services) of all products
     * @param string $product_sku Product sku
     * @param array $products The resulting array products
     * @return array Product data
     */
    public static function searchProductArray(string $product_sku, array $products): array
    {
        $found_key = array_search($product_sku, array_column($products, 'sku'));

        if (!$found_key) return [];

        return $products[$found_key];
    }

    public static function productAdd(array $needed_fields, $product): int
    {
        $wc_product = new \WC_Product();

        $functions = array_combine(static::dataFormatSet($needed_fields), $needed_fields);

        call_user_func([$wc_product, 'set_name'], $product['name']);
        call_user_func([$wc_product, 'set_sku'], $product['sku']);

        foreach ($functions as $key => $value) {
            call_user_func([$wc_product, $key], $product[$value]);
        }


        return $wc_product->save();
    }

    /**
     * Product update in woocommerce
     * @param array $needed_fields
     * @param $product_id
     * @param $product
     * @return bool
     */
    public static function productUpdate(array $needed_fields, $product_id, $product): bool
    {
        if (empty($product) || empty($product_id)) return true;

        $wc_product = new \WC_Product($product_id);

        $functions = array_combine(static::dataFormatSet($needed_fields), $needed_fields);

        foreach ($functions as $key => $value) {
            call_user_func([$wc_product, $key], $product[$value]);
        }

        $wc_product->save();

        return true;
    }

    /**
     * Get product data from woocommerce
     * @param array $needed_fields
     * @param $product_id
     * @param $product
     * @return bool
     */
    public static function productGet(array $needed_fields, $product_id): array
    {
        $wc_product = new \WC_Product($product_id);

        /** @var $get_functions returns functions to query product fields */
        $get_functions = static::dataFormatGet($needed_fields);

        /** @var $needed_fields fields format dkPlus */
        $needed_map = ProductMap::ProductMapReverse($needed_fields);

        $product = [];
        foreach ($get_functions as $key => $field) {
            $product[$needed_map[$key]] = call_user_func([$wc_product, $field]);
        }

        return $product;
    }

    /**
     * Variation update in woocommerce
     * @param array $needed_fields
     * @param $variation_id
     * @param $product
     * @return bool
     */
    public static function variationUpdate(array $needed_fields, $variation_id, $product): bool
    {
        if (empty($product)) return true;

        $wc_variation = new \WC_Product_Variation($variation_id);

        $functions = array_combine(static::dataFormatSet($needed_fields), $needed_fields);

        foreach ($functions as $key => $value) {
            call_user_func([$wc_variation, $key], $product[$value]);
        }

        $wc_variation->save();

        return true;
    }

    /**
     * Get variation from woocommerce product
     * @param array $needed_fields
     * @param $variation_id
     * @param $product
     * @return bool
     */
    public static function variationGet(array $needed_fields, $variation_id): array
    {
        $wc_product = new \WC_Product_Variation($variation_id);

        /** @var $functions returns functions to query product fields */
        $functions = static::dataFormatGet($needed_fields);

        /** @var $needed_fields fields format dkPlus */
        $needed_map = ProductMap::ProductMapReverse($needed_fields);

        $product = [];
        foreach ($functions as $key => $field) {
            $product[$needed_map[$key]] = call_user_func([$wc_product, $field]);
        }

        return $product;
    }

    public static function dataFormatSet(array $product_data): array
    {
        foreach ($product_data as &$value) {
            $value = 'set_' . $value;
        }

        return $product_data;
    }

    public static function dataFormatGet(array $product_data): array
    {
        foreach ($product_data as &$value) {
            $value = 'get_' . $value;
        }

        return $product_data;
    }


}