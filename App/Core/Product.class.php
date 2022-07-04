<?php

namespace woo_bookkeeping\App\Core;

abstract class Product extends Woo_Query
{
    abstract protected static function productSyncOne(array $needed_fields, $product_id);

    abstract protected static function productSyncAll(array $needed_fields);

    /**
     * Search for a product in the resulting array of all products
     * @param string $product_sku Product sku
     * @param array $products The resulting array products
     * @param $sku_field_name SKU field name in array
     * @return array Product data
     */
    public static function searchProduct(string $product_sku, array $products, string $sku_field_name): array
    {
        $found_key = array_search($product_sku, array_column($products, $sku_field_name));

        if (!$found_key) return [];

        return $products[$found_key];
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
        $wc_product = new \WC_Product($product_id);

        foreach ($product as $key => $value) {
            if (!in_array($key, $needed_fields)) continue;

            call_user_func([$wc_product, $key], $value);
        }

        $wc_product->save();

        return true;
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
        $wc_variation = new \WC_Product_Variation($variation_id);

        foreach ($product as $key => $value) {
            if (!in_array($key, $needed_fields)) continue;

            call_user_func([$wc_variation, $key], $value);
        }

        $wc_variation->save();

        return true;
    }

}