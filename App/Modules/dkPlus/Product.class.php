<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\Woo_Query;

class Product extends \woo_bookkeeping\App\Core\Product
{

    public static function productSyncOne(array $needed_fields, $product_id): bool
    {
        $product_sku = Woo_Query::getProduct('sku', $product_id)['sku'];
        /*$product = wc_get_product( $product_id );
        $product_sku = $product->get_sku();*/

        $product = API::productFetchOne($product_sku);

        self::productUpdate($needed_fields, $product_id, $product_sku, $product);

        return true;
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