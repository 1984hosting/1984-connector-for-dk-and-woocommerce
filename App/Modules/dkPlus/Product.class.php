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
        $product_id = $data['product_id'] ?? 0;
        $product_sku = $data['sku'] ?? 0;

        $product_children = Woo_Query::getChildren($product_id);

        if ($product_children) {
            foreach ($product_children as $child_id) {
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
     * @param int $product_id
     * @return array product data
     */
    public static function productSyncOne(array $needed_fields = [], int $product_id = 0): array
    {
        if (empty($needed_fields) || $product_id === 0) {
            $data = $_POST['data'];

            if (empty($data['sync_params'])) return [];

            $needed_fields = $data['sync_params'];
            $product_id = $data['product_id'];
            $product_sku = $data['sku'];
        } else {
            $wc_product = Woo_Query::getProduct('sku', $product_id);

            //no sku specified, product not associated with dk
            if (empty($wc_product['sku'])) return [];

            $product_sku = $wc_product['sku'];
        }

        $product_children = Woo_Query::getChildren($product_id);

        if ($product_children) {
            foreach ($product_children as $child_id) {
                self::variationSync($needed_fields, $child_id);
            }
            //variations sync is completed, return true
            return [];
        }

        if (empty($product_sku)) return [];

        $product = API::productFetchOne($product_sku);

        self::productUpdate($needed_fields, $product_id, $product);

        return $product;
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
        $needed_fields = $_POST['sync_params'];

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

    public static function productSendQty($product_sku, $qty): bool
    {
        $product = [
            'UnitQuantity' => $qty,
        ];

        return API::productUpdateDK($product_sku, $product);
    }


    /**
     * Import products from dk
     * @param array $needed_fields - Required fields for synchronization
     * @return bool
     */
    public static function productImportAll(): array
    {
        $needed_fields = $_POST['sync_params'];

        $products = Woo_Query::getProducts('product_id, sku, tax_class');
        $dkProducts = API::productFetchAll();

        if (empty($dkProducts)) return ['status' => true];

        foreach ($dkProducts as $product) {
            $product_prop = self::searchProductArray($product['sku'], $products);

            /** Check product if exists */
            if (!empty($product_prop)) {
                /** If the product has variations, then update the variations */
                if ($product_prop['tax_class'] === 'parent') {
                    self::variationUpdate($needed_fields, $product_prop['product_id'], $product);
                } else {
                    self::productUpdate($needed_fields, $product_prop['product_id'], $product);
                }
            } else {
                self::productAdd($needed_fields, $product);
            }


        }

        return ['status' => true];
    }

    public static function add_to_cart_validation($passed, $product_id, $quantity)
    {
        self::productSyncOne([
            'regular_price',
            'stock_quantity',
        ], $product_id);

        return $passed;
    }

    public static function before_checkout_process()
    {
        foreach (WC()->cart->get_cart() as $cart_item) {
            $product_id = $cart_item['product_id'];

            $product = self::productSyncOne([
                'regular_price',
                'stock_quantity',
            ], $product_id);

            $qty = $product['stock_quantity'] - 1;
            /*var_dump($qty);
            die();*/
            self::productSendQty($product['sku'], $qty);

            return true;
        }
    }


    public static function change_order_status($order_id, $old_status, $new_status)
    {

        /*$order = wc_get_order($order_id);

        //$order_total = $order->get_formatted_order_total();
        $order_total = $order->get_total();

        die($order_total);*/
    }

    public static function registerActions()
    {
        add_filter('woocommerce_add_to_cart_validation', [self::class, 'add_to_cart_validation'], 10, 5);
        add_action('woocommerce_before_checkout_process', [self::class, 'before_checkout_process']);
        add_action('woocommerce_order_status_changed', [self::class, 'change_order_status'], 10, 3);
        //add_action( 'woocommerce_checkout_process', 'woocommerce_checkout_process_action' );
    }

}