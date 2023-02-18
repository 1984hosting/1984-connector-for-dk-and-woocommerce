<?php
/**
 * The file that defines the Product class
 *
 * A class definition that includes attributes and functions of the Product class
 *
 * @since      0.1
 *
 * @package    WooCoo
 * @subpackage WooCoo/App/Modules/dkPlus
 */

namespace woocoo\App\Modules\dkPlus;

use woocoo\App\Core\Woo_Query;
use woocoo\App\Core\Logs;

/**
 * Class Product
 */
class Product extends \woocoo\App\Core\Product
{
    private static int $import_slice = 35; //how many products to import per iteration (more php execution limit more number)

    private static int $sync_slice = 35; //how many products to sync per iteration (more php execution limit more number)

    public function __construct()
    {
        $this->registerActions();
    }

    /**
     * Performing a sync for a single product
     *
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
                Main::productUpdateDK($product_sku, $variation);
            }
            //variations sync is completed, return true
            // return true;
        }

        if (!$product_sku) {
            $product = Woo_Query::getProduct('sku', $product_id);
            $product_sku = $product['sku'];
        }

        $product = self::productGet($needed_fields, $product_id);

        return Main::productUpdateDK($product_sku, $product);
    }

    /**
     * Performing a sync for a single product
     *
     * @param array $needed_fields needed fields for sync
     * @param int $product_id
     * @return array product data
     * @throws \WC_Data_Exception
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
            // return [];
        }

        if (empty($product_sku)) return [];

        $product = Main::productFetchOne($product_sku);

        self::productUpdate($needed_fields, $product_id, $product);

        return $product;
    }

    /**
     * Performing a sync for product variation
     *
     * @param array $needed_fields needed fields for sync
     * @param int $variation_id
     * @return bool
     */
    public static function variationSync(array $needed_fields, int $variation_id): bool
    {
        $product = Woo_Query::getProduct('sku, product_id', $variation_id);

        if (empty($product['sku'])) return false;

        $product_dk = Main::productFetchOne($product['sku']);

        return self::variationUpdate($needed_fields, $product['product_id'], $product_dk);
    }

    /**
     * Synchronization request from the admin panel (sync all products)
     *
     * @return array
     */
    public static function productSyncAll(): array
    {
        Logs::appendLog(Main::$module_slug . '/logs', 'Start product sync');

        $needed_fields = $_POST['sync_params'] ?? Main::getInstance()[Main::$module_slug]['schedule']['params'];
        if (empty($needed_fields)) {
            Logs::appendLog(Main::$module_slug . '/logs', 'Please select at least one property to sync');
            return [
                'status' => 'empty',
                'message' => __('Please select at least one property to sync', PLUGIN_SLUG),
            ];
        }

        $existing_products = Woo_Query::getProducts('product_id, sku, tax_class');
        if (empty($existing_products)) {
            Logs::appendLog(Main::$module_slug . '/logs', 'Missing products for sync');
            return [
                'status' => 'empty',
                'message' => __('Missing products for sync, please add products', PLUGIN_SLUG),
            ];
        }

        $dkProducts = Main::productFetchAll();
        if (empty($dkProducts)) {
            Logs::appendLog(Main::$module_slug . '/logs', 'Missing products for sync');
            return [
                'status' => 'empty',
                'message' => __('no products for sync on DK side', PLUGIN_SLUG),
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
            'message' => __('Synchronization in progress ...', PLUGIN_SLUG)
        ];
    }

    /**
     * All Products Sync Schedule
     *
     * @return void
     */
    public static function productSyncAllSchedule(): void
    {
        $sync_products_status = Logs::readLog(Main::$module_slug . '/sync_products_status');

        if (!isset($sync_products_status['status']) || $sync_products_status['status'] === 'success') {
            Product::productSyncAll();
        } else {
            Logs::appendLog(Main::$module_slug . '/logs', __('New sync start failed (Previous sync not completed)', PLUGIN_SLUG));
        }
    }

    /**
     * Synchronization in progress
     *
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
            $sync_products_status['message'] = __('Synchronization in progress, please do not close the tab', PLUGIN_SLUG);
        } else {
            $products = self::productsSync($sync_products['needed_fields'], $sync_products['products']);
            $sync_products_status['count_products'] = count($products);
            $sync_products_status['status'] = $sync_products_status['count_products'] > 0 ? 'prolong' : 'success';
            $sync_products_status['completed_percent'] = calc_percent($sync_products_status['start_count_products'], $sync_products_status['count_products']);
            $sync_products_status['message'] = $sync_products_status['count_products'] > 0 ? __('Synchronization in progress, please do not close the tab', PLUGIN_SLUG) : __('Sync products is successfully completed', PLUGIN_SLUG);
        }

        $sync_products['products'] = $products;

        Logs::writeLog(Main::$module_slug . '/sync_products', $sync_products);
        Logs::writeLog(Main::$module_slug . '/sync_products_status', $sync_products_status);

        if ($sync_products_status['status'] === 'success') {
            Logs::appendLog(Main::$module_slug . '/logs', sprintf(__('Sync products is successfully completed (%d product\'s)', PLUGIN_SLUG), $sync_products_status['start_count_products']));
        }

        return $sync_products_status;
    }

    /**
     * Import products from dk
     *
     * @return array
     */
    public static function productsImport(): array
    {
        if (!empty($_POST['sync_params'])) {
            $needed_fields = $_POST['sync_params'];
            $products = Main::productFetchAll();
            $import_products_status['start_count_products'] = count($products);
        } else {
            $import_products = Logs::readLog(Main::$module_slug . '/import_products');
            $import_products_status = Logs::readLog(Main::$module_slug . '/import_products_status');
            $products = $import_products['products'] ?? [];
            $needed_fields = $import_products['needed_fields'] ?? [];
        }
        if (empty($needed_fields)) {
            Logs::appendLog(Main::$module_slug . '/logs', __('Please select import properties', PLUGIN_SLUG));
            return [
                'status' => 'empty',
                'message' => __('Please select import properties', PLUGIN_SLUG),
            ];
        }
        if (empty($products)) {
            Logs::appendLog(Main::$module_slug . '/logs', __('No data to import, please try again later', PLUGIN_SLUG));
            return [
                'status' => 'empty',
                'message' => __('No data to import, please try again later', PLUGIN_SLUG),
            ];
        }

        // Import from dkPlus: Products existing in WooCommerce should be updated when imported. #28
        $wooproducts = Woo_Query::getProducts('product_id,sku');
        $updateproducts = [];
        foreach ($products as $product) {
            $product_prop = self::searchProductArray($product['sku'], $wooproducts);
            if (!empty($product_prop)) {
                $updateproducts[] = array_merge($product_prop, $product);
            }
        }

        $existing_products = self::getAllProductsSKU();
        $products = array_merge($updateproducts, self::filterProducts($products, $existing_products));

        if (empty($products)) {
            Logs::appendLog(Main::$module_slug . '/logs', __('New products for import not found', PLUGIN_SLUG));
            return [
                'status' => 'empty',
                'message' => __('New products for import not found', PLUGIN_SLUG),
            ];
        }

        if (isset($_POST['sync_params'])) {
            $import_products_status['start_count_products'] = count($products);
        }

        $import_products['products'] = $products;
        $import_products['needed_fields'] = $needed_fields;
        $import_products['existing_products'] = $existing_products;

        $import_products_status['count_products'] = count($products);
        $import_products_status['message'] = $import_products_status['count_products'] > 0 ? __('Import in progress, please do not close the tab', PLUGIN_SLUG) : __('Import products is successfully completed', PLUGIN_SLUG);
        $import_products_status['status'] = $import_products_status['count_products'] > 0 ? 'prolong' : 'success';
        $import_products_status['completed_percent'] = calc_percent($import_products_status['start_count_products'], $import_products_status['count_products']);

        Logs::writeLog(Main::$module_slug . '/import_products', $import_products);
        Logs::writeLog(Main::$module_slug . '/import_products_status', $import_products_status);

        if ($import_products_status['status'] === 'success') {
            Logs::appendLog(Main::$module_slug . '/logs', sprintf(__('Import products is successfully completed (%d products)', PLUGIN_SLUG), $import_products_status['start_count_products']));
        } else {
            Logs::appendLog(Main::$module_slug . '/logs', sprintf(__('Product import started (%d products)', PLUGIN_SLUG), $import_products_status['start_count_products']));
        }

        return $import_products_status;
    }

    /**
     * Products Import Prolong
     *
     * @return array
     */
    public static function productsImportProlong(): array
    {
        $import_products = Logs::readLog(Main::$module_slug . '/import_products');
        $import_products_status = Logs::readLog(Main::$module_slug . '/import_products_status');

        $products = self::productsAdd($import_products['needed_fields'], $import_products['products']);

        $import_products_status['count_products'] = count($products);
        $import_products_status['status'] = $import_products_status['count_products'] > 0 ? 'prolong' : 'success';
        $import_products_status['completed_percent'] = calc_percent($import_products_status['start_count_products'], $import_products_status['count_products']);
        $import_products_status['message'] = $import_products_status['count_products'] > 0 ? __('Import in progress, please do not close the tab', PLUGIN_SLUG) : __('Import products is successfully completed', PLUGIN_SLUG);

        $import_products['products'] = $products;

        Logs::writeLog(Main::$module_slug . '/import_products', $import_products);
        Logs::writeLog(Main::$module_slug . '/import_products_status', $import_products_status);

        if ($import_products_status['status'] === 'success') {
            Logs::appendLog(Main::$module_slug . '/logs', sprintf(__('Import products is successfully completed (%d products)', PLUGIN_SLUG),$import_products_status['start_count_products']));
        }

        return $import_products_status;
    }

    /**
     * Get Status
     *
     * @return array
     */
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
     *
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
     *
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
     *
     * @param $product_sku - product sku
     * @param $qty - new qty
     * @return bool
     */
    public static function productSendQty($product_sku, $qty): bool
    {
        $product['UnitQuantity'] = $qty;

        return Main::productUpdateDK($product_sku, $product);
    }

    /**
     * Add Products
     *
     * @param $needed_fields - needed fields to import
     * @param $dkProducts - array products from DK
     * @return array - products without imported products
     * @throws \WC_Data_Exception
     */
    public static function productsAdd($needed_fields, $dkProducts): array
    {
        $count = self::$import_slice;
        $count_products = count($dkProducts);

        $settings = Main::getInstance();
        $params = $settings[Main::$module_slug]['schedule']['params'];

        if ($count > $count_products) $count = $count_products;

        $import_products = $dkProducts;

        for ($i = $count; $i > 0; $i--) {
            $product = array_shift($dkProducts);
            // update product if it has id and exist #28
            if (isset($product['product_id'])) {
                // Product field should be updated, if it was set in "dkPlus synchronization" product tab #30
                $updated_fields = [];
                foreach ($needed_fields as $key) {
                    $value = get_post_meta($product['product_id'], '_woocoo_' . $key, true);
                    if ($value) {
                        if ($value === 'on') {
                            $updated_fields[] = $key;
                        }
                    } else {
                        // Product field should be updated, if it was set in "Woo Bookkeeping/dkPlus" settings tab #30
                        if (array_search($key, $params) !== false) {
                            $updated_fields[] = $key;
                        }
                    }
                }
                self::productUpdate($updated_fields, $product['product_id'], $product);
            } else {
                self::productAdd($needed_fields, $product, $import_products);
            }
        }

        return $dkProducts;
    }

    /**
     * Sync Products
     *
     * @param $needed_fields
     * @param $dkProducts
     * @return array
     * @throws \WC_Data_Exception
     */
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

    /**
     * Add to cart validation
     *
     * @param $passed
     * @param $product_id
     * @param $quantity
     * @return mixed
     * @throws \WC_Data_Exception
     */
    public static function add_to_cart_validation($passed, $product_id, $quantity)
    {
        self::productSyncOne([
            'regular_price',
            'stock_quantity',
        ], $product_id);

        return $passed;
    }

    /**
     * Before checkout process
     *
     * @return bool
     * @throws \WC_Data_Exception
     */
    public static function before_checkout_process()
    {
        foreach (WC()->cart->get_cart() as $cart_item) {
            $product_id = $cart_item['product_id'];
            $variation_id = $cart_item['variation_id'];
            if ($variation_id) {
                $product_with_stock = wc_get_product($variation_id);
            } else {
                $product_with_stock = wc_get_product($product_id);
            }
            $quantity = $cart_item['quantity'];

            $product = self::productSyncOne([
                'regular_price',
                'stock_quantity',
            ], $product_id);

            // Item quantity in DK should be reduced by same amount as ordered. #31
            $qty = $product_with_stock->get_stock_quantity() - $quantity;
            self::productSendQty($product_with_stock->get_sku(), $qty);
        }
        return true;
    }

    /**
     * Order edit status
     *
     * @param $id
     * @param $new_status
     * @return void
     * @throws \WC_Data_Exception
     */
    public static function order_edit_status($id, $new_status)
    {
        $order = wc_get_order($id);
        $items = $order->get_items();

        foreach ($items as $item) {
            $product = $item->get_product();
            $dk_product = Main::productFetchOne($product->sku);

            self::productUpdate(['stock_quantity'], $product->id, $dk_product);
        }
    }

    /**
     * Order changed status
     *
     * @param $order_id
     * @param $old_status
     * @param $new_status
     * @return void
     */
    public static function order_changed_status($order_id, $old_status, $new_status)
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

    /**
     * Admin notices
     *
     * @return void
     */
    public static function admin_notices(){
        $admin_notice = Logs::readLog(Main::$module_slug . '/admin_notice');
        if (!empty($admin_notice['message'])) {
            echo '<div class="woocoo-notice  notice notice-'. $admin_notice['status'] .' is-dismissible">
                  <span class="dashicons dashicons-buddicons-activity"></span>
                  <p>'. $admin_notice['message'] .'</p>
             </div>';
            Logs::writeLog(Main::$module_slug . '/admin_notice', []);
        }
    }

    /**
     * Register Actions
     *
     * @return void
     */
    private function registerActions()
    {
        add_filter('woocommerce_add_to_cart_validation', [self::class, 'add_to_cart_validation'], 10, 5);

        // WooCommerce: An invalid phone number during checkout reduces product stock, when it shouldn't. #32
        add_action('woocommerce_before_checkout_process', [self::class, 'before_checkout_process']);

        add_action('admin_notices', [self::class, 'admin_notices']);

        //add_action('woocommerce_order_status_changed', [self::class, 'order_changed_status'], 10, 3);
        //add_action('woocommerce_order_edit_status', [self::class, 'order_edit_status'], 111, 2);

        //add_action('woocommerce_order_item_quantity', [self::class, 'change_order_status'], 10, 3);
        //add_action( 'woocommerce_checkout_process', 'woocommerce_checkout_process_action' );

    }
}
