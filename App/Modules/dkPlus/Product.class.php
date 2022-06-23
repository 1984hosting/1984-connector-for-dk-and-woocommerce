<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\Woo_Query;

class Product extends \woo_bookkeeping\App\Core\Product
{

    public static function productSyncOne(array $needed_fields, $product_id): array
    {
        $product_sku = Woo_Query::getProduct('sku', $product_id)['sku'];

        if (empty($product_sku)) return ['status' => false];

        $product = API::productFetchOne($product_sku);
        $product_update = self::productUpdate($needed_fields, $product_id, $product_sku, $product);

        $result['status'] = $product_update;
        $result['content'] = ProductMap::ProductContentMap($product);

        return $result;
    }

    public static function productSyncAll(array $needed_fields)
    {
        $products = Woo_Query::getProducts('sku, product_id');

        foreach ($products as $product) {
            $product_prop = API::productFetchOne($product['sku']);

            self::productUpdate($needed_fields, $product['product_id'], $product['sku'], $product_prop);
        }
    }
}