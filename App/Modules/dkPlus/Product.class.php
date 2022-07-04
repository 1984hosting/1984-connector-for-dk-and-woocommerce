<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\Woo_Query;

class Product extends \woo_bookkeeping\App\Core\Product
{

    /**
     * Performing a sync for a single product
     * @param array $needed_fields needed fields for sync
     * @param $product_id
     * @return bool
     */
    public static function productSyncOne(array $needed_fields, $product_id): bool
    {
        $product = Woo_Query::getProduct('sku, product_id', $product_id);
        $product_id = $product['product_id'];
        $product_sku = $product['sku'];
        $product_children = Woo_Query::getChildren($product_id);

        if ($product_children) {
            foreach($product_children as $child_id) {
                self::variationSync($needed_fields, $child_id);
            }
            //variations sync is completed, return true
            return true;
        }

        if (empty($product_sku)) return false;

        $product = API::productFetchOne($product_sku);

        return self::productUpdate($needed_fields, $product_id, $product);
    }

    /**
     * Performing a sync for product variation
     * @param array $needed_fields needed fields for sync
     * @param int $variation_id
     * @return bool
     */
    public static function variationSync(array $needed_fields, int $variation_id): bool
    {
        $product = Woo_Query::getProduct('sku, product_id', $variation_id);
        $product_id = $product['product_id'];
        $product_sku = $product['sku'];

        if (empty($product_sku)) return false;

        $product = API::productFetchOne($product_sku);

        return self::variationUpdate($needed_fields, $product_id, $product);
    }

    /**
     * Performing a sync for an all products
     * @param array $needed_fields - Required fields for synchronization
     * @return array|false[]
     */
    public static function productSyncAll(array $needed_fields): array
    {
        $products = Woo_Query::getProducts('*');
        $dkProducts = API::productFetchAll();

        if (empty($dkProducts) || empty($products)) return ['status' => true];

        foreach ($products as $product) {
            $product_children = Woo_Query::getChildren($product['product_id']);

            /**
             * If the product has variations, then update the variations
             */
            if ($product_children) {
                foreach ($product_children as $child_id) {
                    self::variationSync($needed_fields, $child_id);
                }
            } else {
                $product_prop = self::searchProduct($product['sku'], $dkProducts, 'sku');

                var_dump($product, $product_children);
                self::productUpdate($needed_fields, $product['product_id'], $product_prop);
            }
        }

        return ['status' => true];
    }
}