<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\Woo_Query;

class Product extends \woo_bookkeeping\App\Core\Product
{
    /**
     * Performing a sync for a single product
     * @param array $needed_fields - Required fields for synchronization
     * @param $product_id
     * @return array|false[]
     */
    public static function productSyncOne(array $needed_fields, $product_id): array
    {
        $product_sku = Woo_Query::getProduct('sku', $product_id)['sku'];

        if (empty($product_sku)) return ['status' => false];

        $product = API::productFetchOne($product_sku);
        $product_update = self::productUpdate($needed_fields, $product_id, $product);

        $result['status'] = $product_update;
        $result['content'] = ProductMap::ProductContentMap($product);

        return $result;
    }

    /**
     * Performing a sync for an all products
     * @param array $needed_fields - Required fields for synchronization
     * @return array|false[]
     */
    public static function productSyncAll(array $needed_fields): array
    {
        $products = Woo_Query::getProducts('sku, product_id');
        $dkProducts = API::productFetchAll();

        if (empty($dkProducts) || empty($product)) return ['status' => true];

        foreach ($products as $product) {
            $product_prop = self::searchProduct($product['sku'], $dkProducts, 'sku');
            self::productUpdate($needed_fields, $product['product_id'], $product_prop);
        }

        return ['status' => true];
    }

}