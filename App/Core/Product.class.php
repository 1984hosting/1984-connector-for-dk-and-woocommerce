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
    public function searchProduct(string $product_sku, array $products, $sku_field_name): array
    {
        $found_key = array_search($product_sku, array_column($products, $sku_field_name));

        if (!$found_key) return [];

        return $products[$found_key];
    }

    public static function productUpdate(array $needed_fields, $product_id, $product_sku, $product): bool
    {
        $wc_product = new \WC_Product($product_id);

        foreach ($product as $key => $value) {
            if (!in_array($key, $needed_fields)) continue;

            call_user_func([$wc_product, $key], $value);
        }

        $wc_product->save();

        return true;
    }



    /*public function productSyncAll(array $needed_fields): bool
    {
        $dkPlus_products = $this->productFetchAll(); //todo set classmapper

        if (empty($dkPlus_products)) return true;

        $woo_products = Woo_Query::getProducts('product_id, sku');

        foreach ($woo_products as $woo_product) {
            $product_id = (int)$woo_product['product_id'];
            $product_sku = $woo_product['sku'];

            $found_key = array_search($product_sku, array_column($dkPlus_products, 'ItemCode'));

            if (!$found_key) continue;

            $product_data = $this->productData($product_id, $needed_fields, $dkPlus_products[$found_key]);
        }

        if (empty($product_data)) {
            return true;
        }

        foreach ($product_data as $item) {
            foreach ($item as $table => $data) {
                $this->update($data['fields'], $table, $data['where']);
            }
        }

        return true;
    }*/

}