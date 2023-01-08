<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\Ajax;
use woo_bookkeeping\App\Core\Logs;

class Page extends \woo_bookkeeping\App\Core\Page
{
    function __construct()
    {
        $settings = Main::getInstance();

        /**
         * If dk params isn't valid, disable tab for the product
         */
        if (empty($settings[Main::$module_slug]['token'])) return;

        $this->registerActions();
    }

    /**
     * Add a custom product data tab
     */
    public function product_tab_create($default_tabs)
    {
        $default_tabs['custom_tab'] = [
            'label' => __('dkPlus synchronization', PLUGIN_SLUG),
            'target' => 'product_tab_content',
            'priority' => 25,
            'class' => []
        ];
        return $default_tabs;
    }

    /**
     * Adds a sync tab for the product
     */
    public function product_tab_content()
    {
        include_once PLUGIN_TPL_DIR . '/dkPlus/product-tab.php';
    }

    public function create_meta_box()
    {
        add_meta_box(
            'dkplus', //id
            'dkPlus sync', //title
            [$this, 'meta_box_content'], //callback content function
            'product', //post type
            'side', //position (normal, side, advanced)
            'high' //priority (default, low, high, core)
        );

    }

    /**
     * Save sync options for the product
     */
    public function process_product_object( $product )
    {

        $keys = array(
            'name',
            'description',
            'regular_price',
            'stock_quantity',
            'manage_stock',
        );

        // Saving the sync options for the product, that were set in "dkPlus synchronization" product tab #30
        foreach ($keys as $key) {
            update_post_meta( $product->get_id(), '_woocoo_' . $key, ($_POST[$key] === 'on')?$_POST[$key]:'off' );
        }

    }

    /**
     * Create invoice when purchase is complete. #23
     */
    public function payment_complete( $order_id ){
        $order = wc_get_order( $order_id );
        $user = $order->get_user();
        if( $user ){
            $customer = Main::customerSearch($user->user_nicename);
            if (!$customer) {
                $salesperson = Main::salesPersonFetchOne('webshop');
                if (!$salesperson) {
                    $employee = Main::generalEmployeeFetchOne('woocoo');
                    if (!$employee) {
                        $data = [
                            "Number" => 'woocoo',
                            "Name" => get_bloginfo('name')
                        ];
                        $employee = Main::generalEmployeeCreate($data);
                    }
                    $data = [
                        "Number" => "webshop",
                        "Employee" => "woocoo",
                        "NameOnSalesOrders" => "webshop",
                        "Warehouse" => "bg1"
                    ];
                    $salesperson = Main::salesPersonCreate($data);
                }
                $data = [
                    "Number" => $user->ID,
                    "Name" => $user->display_name,
                    "Alias" => $user->user_nicename,
                    "Address1" => $order->get_billing_address_1(),
                    "Email" => $user->user_email,
                    "Salesperson" => "webshop"
                ];
                $customer = Main::customerCreate($data);
            } else {
                $customer = array_shift($customer);
            }

            $customer = array_shift($customer);
            $lines = [];
            $order_items    = $order->get_items();
            foreach ( $order_items as $order_item ) {
                $product = $order_item->get_product();
                $lines[] = [
                    "ItemCode" => $product->get_sku(),
                    "Text" => $order_item->get_name(),
                    "Quantity" => $order_item->get_quantity(),
                    "IncludingVAT" => true,
                    "Price" => wc_get_price_excluding_tax($product),
                ];
            }

            $data = [
                "Date" => get_date_from_gmt( $order->get_date_paid()->date('c'), "c" ),
                "Customer" => [
                    "Number" => $customer["Number"],
                    "Name" => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                    "ZipCode" => $order->get_billing_postcode(),
                    "Country" => $order->get_billing_country(),
                    "Address1" => $order->get_billing_city() . ', ' . $order->get_billing_address_1()
                ],
                "Payments" => [
                    [
                        "ID" => 14,
                        "Name" => "Mastercard",
                        "Amount" => $order->get_total()
                    ]
                ],
                "Lines" => $lines
            ];

            $invoice = Main::salesCreateInvoice($data);
            if ($invoice) {
                Logs::appendLog(Main::$module_slug . '/logs', 'Invoice #'. $invoice['Number'] .' is successfully added');
            }
        }
    }

    public function meta_box_content($post)
    {
        //print_r($post);
        include_once PLUGIN_TPL_DIR . '/dkPlus/product-meta_content.php';
    }

    /**
     * Saving Options and Installing a Cron Job
     */
    public static function saveOptions()
    {
        $settings = Main::getInstance();
        $data = $_POST;
        $settings[Main::$module_slug]['schedule']['params'] = $data['sync_params'];
        $task_name = 'woocoo_update_' . Main::$module_slug;
        unset($settings[Main::$module_slug]['schedule']['name']);

        /** Remove old events */
        wp_unschedule_hook($task_name);

        /** Add events */
        //wp_schedule_event(time(), 'every_minute', $regular_task_name);
        if (isset($data['woocoo_schedule']) && $data['woocoo_schedule'] !== 'disabled' && $settings[Main::$module_slug]['token']) {
            $settings[Main::$module_slug]['schedule']['name'] = $data['woocoo_schedule'];

            wp_schedule_event(time(), $data['woocoo_schedule'], $task_name);
        }

        $update_otion = update_option(PLUGIN_SLUG, $settings, 'no');

        return [
            'status' => 'success',
            'message' => 'Settings saved successfully',
        ];
    }

    public static function incompleteImport()
    {
        return Logs::readLog('dkPlus/import_products_status');
    }

    public static function incompleteSync()
    {
        return Logs::readLog('dkPlus/sync_products_status');
    }

    /**
     * Register required actions
     */
    private function registerActions()
    {
        add_filter('woocommerce_product_data_tabs', [$this, 'product_tab_create'], 10, 1);
        add_action('woocommerce_product_data_panels', [$this, 'product_tab_content']);
        add_action('add_meta_boxes', [$this, 'create_meta_box']);
        add_action( 'woocommerce_admin_process_product_object', [$this, 'process_product_object'], 10, 1 );

        // Create invoice when purchase is complete. #23
        add_action( 'woocommerce_payment_complete', [$this, 'payment_complete'], 10, 1  );

        /** Ajax actions */
        new Ajax(Main::$module_slug . '_save', function () {
            $response = Page::saveOptions();

            AJAX::response($response);
        });
        new Ajax(Main::$module_slug . '_sync', function () {
            $response = Product::productSyncAll();

            AJAX::response($response);
        });
        new Ajax(Main::$module_slug . '_sync_product_one', function () {
            Product::productSyncOne();

            AJAX::response([
                'status' => 'success',
                'message' => 'The product has been successfully synced, the page will be refreshed now',
            ]);
        });
        new Ajax(Main::$module_slug . '_send_to', function () {
            Product::productSend();

            AJAX::response([
                'status' => 'success',
                'message' => 'The product data successfully sent to dkPlus',
            ]);
        });
        new Ajax(Main::$module_slug . '_import', function () {
            $response = Product::productsImport();

            AJAX::response($response);
        });
        new Ajax(Main::$module_slug . '_import_prolong', function () {
            $response = Product::productsImportProlong();

            AJAX::response($response);
        });
        new Ajax(Main::$module_slug . '_import_refresh', function () {
            $response = Product::productsImport();

            AJAX::response($response);
        });
        new Ajax(Main::$module_slug . '_status', function () {
            $response = Product::getStatus();

            AJAX::response($response);
        });
        new Ajax(Main::$module_slug . '_logs_clear', function () {
            $response = Logs::removeLog('dkPlus/logs');

            AJAX::response(['status' => $response]);
        });
    }
}
