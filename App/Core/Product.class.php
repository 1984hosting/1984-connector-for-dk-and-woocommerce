<?php

namespace woo_bookkeeping\App\Core;

abstract class Product extends Woo_Query
{
    public function __construct()
    {
    }

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
     * @param $product_sku
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
}