<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\Woo_Query;
use woo_bookkeeping\App\Core\Logs;

class Product extends \woo_bookkeeping\App\Core\Product
{
    private static int $import_slice = 35; //how many products to import per iteration (more php execution limit more number)
    private static int $sync_slice = 35; //how many products to sync per iteration (more php execution limit more number)

    public function __construct()
    {
        $this->registerActions();
    }

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
     * Synchronization request from the admin panel (sync all products)
     * @return array|false[]
     */
    public static function productSyncAll(): array
    {
        Logs::appendLog(Main::$module_slug . '/logs', 'Start product sync');

        $needed_fields = $_POST['sync_params'] ?? Main::getInstance()[Main::$module_slug]['schedule']['params'];
        if (empty($needed_fields)) {
            Logs::appendLog(Main::$module_slug . '/logs', 'Please select at least one property to sync');
            return [
                'status' => 'empty',
                'message' => 'Please select at least one property to sync',
            ];
        }

        $existing_products = Woo_Query::getProducts('product_id, sku, tax_class');
        if (empty($existing_products)) {
            Logs::appendLog(Main::$module_slug . '/logs', 'Missing products for sync');
            return [
                'status' => 'empty',
                'message' => 'Missing products for sync, please add products',
            ];
        }

        $dkProducts = API::productFetchAll();
        if (empty($dkProducts)) {
            Logs::appendLog(Main::$module_slug . '/logs', 'Missing products for sync');
            return [
                'status' => 'empty',
                'message' => 'no products for sync on DK side',
            ];
        }

        Logs::writeLog(Main::$module_slug . '/sync_products', [
                'products' => $dkProducts,
                'needed_fields' => $needed_fields,
                'existing_products' => $existing_products,
            ]
        );
        Logs::writeLog(Main::$module_slug . '/sync_products_status', [
            'status' => 'prolong',
            'completed_percent' => 0,
        ]);

        return [
            'status' => 'prolong',
            'message' => 'Synchronization in progress ...'
        ];
    }

    public static function productSyncAllSchedule(): void
    {
        $sync_products_status = Logs::readLog(Main::$module_slug . '/sync_products_status');

        if (!isset($sync_products_status['status']) || $sync_products_status['status'] === 'success') {
            Product::productSyncAll();
        } else {
            Logs::appendLog(Main::$module_slug . '/logs', 'New sync start failed (Previous sync not completed)');
        }
    }

    /**
     * Synchronization in progress
     * @return array
     */
    public static function productProlongSync(): array
    {
        $sync_products_status = Logs::readLog(Main::$module_slug . '/sync_products_status');
        if (empty($sync_products_status) || $sync_products_status['status'] !== 'prolong') return [];
        $sync_products = Logs::readLog(Main::$module_slug . '/sync_products');
        //Bring products to woo import format
        if (!empty($sync_products['existing_products'])) {
            $products = self::compareProducts($sync_products['products'], $sync_products['existing_products']);
            unset($sync_products['existing_products']);
            $sync_products_status['start_count_products'] = count($products);
            $sync_products_status['status'] = 'prolong';
            $sync_products_status['completed_percent'] = 0;
            $sync_products_status['message'] = 'Synchronization in progress, please do not close the tab';
        } else {
            $products = self::productsSync($sync_products['needed_fields'], $sync_products['products']);
            $sync_products_status['count_products'] = count($products);
            $sync_products_status['status'] = $sync_products_status['count_products'] > 0 ? 'prolong' : 'success';
            $sync_products_status['completed_percent'] = calc_percent($sync_products_status['start_count_products'], $sync_products_status['count_products']);
            $sync_products_status['message'] = $sync_products_status['count_products'] > 0 ? 'Synchronization in progress, please do not close the tab' : 'Sync products is successfully completed';
        }

        $sync_products['products'] = $products;

        Logs::writeLog(Main::$module_slug . '/sync_products', $sync_products);
        Logs::writeLog(Main::$module_slug . '/sync_products_status', $sync_products_status);

        if ($sync_products_status['status'] === 'success') {
            Logs::appendLog(Main::$module_slug . '/logs', 'Sync products is successfully completed (' . $sync_products_status['start_count_products'] . ' product\'s)');
        }

        return $sync_products_status;
    }

    /**
     * Import products from dk
     * @param array $needed_fields - Required fields for synchronization
     * @return bool
     */
    public static function productsImport(): array
    {
        if (!empty($_POST['sync_params'])) {
            $needed_fields = $_POST['sync_params'];
            $products = API::productFetchAll();
            $import_products_status['start_count_products'] = count($products);
        } else {
            $import_products = Logs::readLog(Main::$module_slug . '/import_products');
            $import_products_status = Logs::readLog(Main::$module_slug . '/import_products_status');
            $products = $import_products['products'] ?? [];
            $needed_fields = $import_products['needed_fields'] ?? [];
        }
        if (empty($needed_fields)) {
            Logs::appendLog(Main::$module_slug . '/logs', 'Please select import properties');
            return [
                'status' => 'empty',
                'message' => 'Please select import properties',
            ];
        }
        if (empty($products)) {
            Logs::appendLog(Main::$module_slug . '/logs', 'No data to import, please try again later');
            return [
                'status' => 'empty',
                'message' => 'No data to import, please try again later',
            ];
        }

        $existing_products = self::getAllProductsSKU();
        $products = self::filterProducts($products, $existing_products);

        if (empty($products)) {
            Logs::appendLog(Main::$module_slug . '/logs', 'New products for import not found');
            return [
                'status' => 'empty',
                'message' => 'New products for import not found',
            ];
        }

        if (isset($_POST['sync_params'])) {
            $import_products_status['start_count_products'] = count($products);
        }

        $import_products['products'] = $products;
        $import_products['needed_fields'] = $needed_fields;
        $import_products['existing_products'] = $existing_products;

        $import_products_status['count_products'] = count($products);
        $import_products_status['message'] = $import_products_status['count_products'] > 0 ? 'Import in progress, please do not close the tab' : 'Import products is successfully completed';
        $import_products_status['status'] = $import_products_status['count_products'] > 0 ? 'prolong' : 'success';
        $import_products_status['completed_percent'] = calc_percent($import_products_status['start_count_products'], $import_products_status['count_products']);

        Logs::writeLog(Main::$module_slug . '/import_products', $import_products);
        Logs::writeLog(Main::$module_slug . '/import_products_status', $import_products_status);

        if ($import_products_status['status'] === 'success') {
            Logs::appendLog(Main::$module_slug . '/logs', 'Import products is successfully completed (' . $import_products_status['start_count_products'] . ' products)');
        } else {
            Logs::appendLog(Main::$module_slug . '/logs', 'Product import started (' . $import_products_status['start_count_products'] . ' products)');
        }

        return $import_products_status;
    }

    public static function productsImportProlong(): array
    {
        $import_products = Logs::readLog(Main::$module_slug . '/import_products');
        $import_products_status = Logs::readLog(Main::$module_slug . '/import_products_status');

        $products = self::productsAdd($import_products['needed_fields'], $import_products['products']);

        $import_products_status['count_products'] = count($products);
        $import_products_status['status'] = $import_products_status['count_products'] > 0 ? 'prolong' : 'success';
        $import_products_status['completed_percent'] = calc_percent($import_products_status['start_count_products'], $import_products_status['count_products']);
        $import_products_status['message'] = $import_products_status['count_products'] > 0 ? 'Import in progress, please do not close the tab' : 'Import products is successfully completed';

        $import_products['products'] = $products;

        Logs::writeLog(Main::$module_slug . '/import_products', $import_products);
        Logs::writeLog(Main::$module_slug . '/import_products_status', $import_products_status);

        if ($import_products_status['status'] === 'success') {
            Logs::appendLog(Main::$module_slug . '/logs', 'Import products is successfully completed (' . $import_products_status['start_count_products'] . ' products)');
        }

        return $import_products_status;
    }

    public static function getStatus(): array
    {
        $sync_products_status = Logs::readLog(Main::$module_slug . '/sync_products_status');
        $import_products_status = Logs::readLog(Main::$module_slug . '/import_products_status');

        if (isset($sync_products_status['notified'])) $sync_products_status = false;
        if (isset($import_products_status['notified'])) $import_products_status = false;

        if (isset($sync_products_status['status']) && $sync_products_status['status'] === 'success') {
            //$sync_products_status['notified'] = 1;
            Logs::writeLog(Main::$module_slug . '/sync_products_status', []);
        }
        if (isset($import_products_status['status']) && $import_products_status['status'] === 'success') {
            //$import_products_status['notified'] = 1;
            Logs::writeLog(Main::$module_slug . '/import_products_status', []);
        }
        return [
            Main::$module_slug . '_sync' => $sync_products_status,
            Main::$module_slug . '_import' => $import_products_status,
            Main::$module_slug . '_logs' => Logs::readLogs('/dkPlus/logs'),
        ];
    }

    /**
     * Search for new products in the array
     * @param $dkProducts - products array from dkPlus
     * @param $existing_sku - array of sku local products
     * @return array
     */
    private static function filterProducts($dkProducts, $existing_sku): array
    {
        if (!empty($existing_sku)) {
            foreach ($dkProducts as $key => $dk_product) {
                //if existing product
                $key_sku = array_search($dk_product['sku'], $existing_sku);
                if ($key_sku !== false) {
                    unset($existing_sku[$key_sku]);
                    unset($dkProducts[$key]);
                }
            }
        }

        return $dkProducts;
    }

    /**
     * Bring the product into the format for importing voo
     * @param $dkProducts
     * @param $existing_products
     * @return array
     */
    private static function compareProducts($dkProducts, $existing_products): array
    {
        foreach ($dkProducts as &$product) {
            $product_prop = self::searchProductArray($product['sku'], $existing_products);

            if (empty($product_prop)) {
                unset($product);
                continue;
            }

            $product = array_merge($product, $product_prop);
        }

        return $dkProducts;
    }

    /**
     * Updating the number of units in stock
     * @param $product_sku - product sku
     * @param $qty - new qty
     * @return bool
     */
    public static function productSendQty($product_sku, $qty): bool
    {
        $product['UnitQuantity'] = $qty;

        return API::productUpdateDK($product_sku, $product);
    }

    /**
     * @param $count - how many products to import from array
     * @param $needed_fields - needed fields to import
     * @param $dkProducts - array products from DK
     * @return array - products without imported products
     */
    public static function productsAdd($needed_fields, $dkProducts): array
    {
        $count = self::$import_slice;
        $count_products = count($dkProducts);

        if ($count > $count_products) $count = $count_products;

        for ($i = $count; $i > 0; $i--) {
            $product = array_shift($dkProducts);
            self::productAdd($needed_fields, $product);
        }

        return $dkProducts;
    }

    public static function productsSync($needed_fields, $dkProducts): array
    {
        $count = self::$sync_slice;
        $count_products = count($dkProducts);

        if ($count > $count_products) $count = $count_products;

        for ($i = $count; $i > 0; $i--) {
            $product = array_shift($dkProducts);
            /**
             * If the product has variations, then update the variations
             */
            if (isset($product['tax_class']) && $product['tax_class'] === 'parent') {
                self::variationUpdate($needed_fields, $product['product_id'], $product);
            } else {
                self::productUpdate($needed_fields, $product['product_id'], $product);
            }
        }

        return $dkProducts;
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

            self::productSendQty($product['sku'], $qty);
        }

        return true;
    }


    public static function order_edit_status($id, $new_status)
    {
        $order = wc_get_order($id);
        $items = $order->get_items();

        foreach ($items as $item) {
            $product = $item->get_product();
            $dk_product = API::productFetchOne($product->sku);

            self::productUpdate(['stock_quantity'], $product->id, $dk_product);
        }
    }

    public static function order_changed_status($order_id, $old_status, $new_status)
        //public static function change_order_status($qty, $order, $item)
    {
        echo '<pre>';
        $order = wc_get_order($order_id);
        var_dump($order);
        echo '</pre>';
        die();
//print_r(wc_get_order($order_id));die();
        /*$order = wc_get_order($order_id);

        //$order_total = $order->get_formatted_order_total();
        $order_total = $order->get_total();

        die($order_total);*/
    }


    private function registerActions()
    {
        add_filter('woocommerce_add_to_cart_validation', [self::class, 'add_to_cart_validation'], 10, 5);
        add_action('woocommerce_before_checkout_process', [self::class, 'before_checkout_process']);
        //add_action('woocommerce_order_status_changed', [self::class, 'order_changed_status'], 10, 3);
        //add_action('woocommerce_order_edit_status', [self::class, 'order_edit_status'], 111, 2);

        //add_action('woocommerce_order_item_quantity', [self::class, 'change_order_status'], 10, 3);
        //add_action( 'woocommerce_checkout_process', 'woocommerce_checkout_process_action' );
    }
}
