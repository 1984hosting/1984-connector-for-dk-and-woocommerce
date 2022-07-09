<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\Woo_Query;

class Product extends \woo_bookkeeping\App\Core\Product
{

    /**
     * Performing a sync for a single product
     * @return bool
     */
    public static function productSend(): bool
    {
        $data = $_POST['data'];

        if (empty($data['sync_params'])) return true;

        $needed_fields = $data['sync_params'];
        $product_id = $data['product_id'];
        $product_sku = $data['sku'];

        $product_children = Woo_Query::getChildren($product_id);

        if ($product_children) {
            foreach($product_children as $child_id) {
                $product_sku = Woo_Query::getProduct('sku', $child_id)['sku'];
                $variation = self::variationGet($needed_fields, $child_id);
                API::productUpdateDK($product_sku, $variation);
            }
            //variations sync is completed, return true
            return true;
        }

        if (!$product_sku) {
            $product = Woo_Query::getProduct('sku', $product_id);
            $product_sku = $product['sku'];
        }

        $product = self::productGet($needed_fields, $product_id);

        return API::productUpdateDK($product_sku, $product);
    }

    /**
     * Performing a sync for a single product
     * @param array $needed_fields needed fields for sync
     * @param $product_id
     * @return bool
     */
    //public static function productSyncOne(array $needed_fields, $product_id): bool
    public static function productSyncOne(): bool
    {
        $data = $_POST['data'];

        if (empty($data['sync_params'])) return true;

        $needed_fields = $data['sync_params'];
        $product_id = $data['product_id'];
        $product_sku = $data['sku'];

        /*$product = Woo_Query::getProduct('sku, product_id', $product_id);
        $product_id = $product['product_id'];
        $product_sku = $product['sku'];*/
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

        if (empty($product['sku'])) return false;

        $product_dk = API::productFetchOne($product['sku']);

        return self::variationUpdate($needed_fields, $product['product_id'], $product_dk);
    }

    /**
     * Performing a sync for an all products
     * @param array $needed_fields - Required fields for synchronization
     * @return array|false[]
     */
    public static function productSyncAll(): array
    {
        $data = $_POST['data'];
        $needed_fields = $data['sync_params'];

        $products = Woo_Query::getProducts('product_id, sku, tax_class');
        $dkProducts = API::productFetchAll();

        if (empty($dkProducts) || empty($products)) return ['status' => true];

        foreach ($products as $product) {
            $product_prop = self::searchProductArray($product['sku'], $dkProducts);

            /**
             * If the product has variations, then update the variations
             */
            if ($product['tax_class'] === 'parent') {
                self::variationUpdate($needed_fields, $product['product_id'], $product_prop);
            } else {
                self::productUpdate($needed_fields, $product['product_id'], $product_prop);
            }
        }

        return ['status' => true];
    }


}