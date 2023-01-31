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
                    "IncludingVAT" => $product->is_taxable(),
                    "Price" => wc_get_price_excluding_tax($product),
                ];
            }

            foreach( $order->get_items( 'shipping' ) as $item_id => $item ){
                $shipping_method_title       = $item->get_method_title();
                $shipping_method_id          = $item->get_method_id(); // The method ID
                $shipping_method_instance_id = $item->get_instance_id(); // The instance ID
                $shipping_method_total       = $item->get_total();
                $dkPlus_product = get_option('woocommerce_'.$shipping_method_id.'_'.$shipping_method_instance_id.'_settings')['dkPlus_product'];
                if ($dkPlus_product) {
                    $lines[] = [
                        "ItemCode" => $dkPlus_product,
                        "Text" => $shipping_method_title,
                        "Quantity" => 1,
                        "Price" => $shipping_method_total,
                    ];
                }
            }

            $data = [
                "Date" => get_date_from_gmt( $order->get_date_paid()->date('c'), "c" ),
                "Reference" => 'Order#' . $order_id,
                "Text1" => $order->get_customer_note(),
                "Customer" => [
                    "Number" => $customer["Number"],
                    "Name" => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                    "Email" => $order->get_billing_email(),
                    "Phone" => $order->get_billing_phone(),
                    "ZipCode" => $order->get_billing_postcode(),
                    "Country" => $order->get_billing_country(),
                    "Address1" => $order->get_billing_city() . ', ' . $order->get_billing_address_1(),
                    "Address2" => $order->get_billing_address_2(),
                ],
                "Lines" => $lines
            ];

            $payment_gateway = WC()->payment_gateways->payment_gateways()[$order->get_payment_method()];
            $dkPlus_type = $payment_gateway->settings['dkPlus_type'];
            if ($dkPlus_type) {
                $data["Payments"] = [
                    [
                        "ID" => $dkPlus_type,
                        "Amount" => $order->get_total()
                    ]
                ];
            }

            if (isset($_POST['ship_to_different_address'])) {
                $data["Receiver"] = [
                                        "Name" => $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(),
                                        "Address1" => $order->get_shipping_address_1(),
                                        "Address2" => $order->get_shipping_address_2(),
                                        "ZipCode" => $order->get_shipping_postcode(),
                                        "City" => $order->get_shipping_city(),
                                    ];
            }

            $invoice = Main::salesCreateInvoice($data);
            if ($invoice) {
                // Add the note
                $order->add_order_note( 'Invoice #'. $invoice['Number'] .' is successfully added' );
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
        as_unschedule_all_actions($task_name);

        /** Add events */
        if (isset($data['woocoo_schedule']) && $data['woocoo_schedule'] !== 'disabled' && $settings[Main::$module_slug]['token']) {
            $schedules = wp_get_schedules();
            $interval = 60;
            if (isset($schedules[$data['woocoo_schedule']])) {
                $interval = $schedules[$data['woocoo_schedule']]['interval'];
            }
            $settings[Main::$module_slug]['schedule']['name'] = $data['woocoo_schedule'];
            as_schedule_recurring_action( time(), $interval, $task_name, array(), PLUGIN_SLUG  );
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

    public function payment_form_fields( $form_fields ){

        $paymentTypesOptions = [];
        $paymentTypesOptions[] = "None";
        $paymentTypes = Main::salesPaymentType();
        foreach($paymentTypes as $paymentType) {
            $paymentTypesOptions[$paymentType['PaymentId']] = $paymentType['Name'];
        }

        $form_fields['dkPlus_type'] = array(
            'title'       => __( 'DkPlus Payment Type', 'woocommerce' ),
            'type'        => 'select',
            'description' => __( 'Choose which type of DKPLUS Payment to set.', 'woocommerce' ),
            'default'     => 'html',
            'class'       => 'dkplus_payment_type wc-enhanced-select',
            'options'     => $paymentTypesOptions,
            'desc_tip'    => true,
        );
        return $form_fields;
    }

    public function shipping_form_fields( $form_fields ){

        $shippingCostOptions = [];
        $shippingCostOptions[] = "None";
        $dkProducts = Main::productFetchAll();
        foreach($dkProducts as $dkProduct) {
            $shippingCostOptions[$dkProduct['sku']] = $dkProduct['name'];
        }

        $form_fields['dkPlus_product'] = array(
            'title'       => __( 'DkPlus Shipping Cost Product', 'woocommerce' ),
            'type'        => 'select',
            'description' => __( 'Choose which DkPlus Product to set as Shipping Cost.', 'woocommerce' ),
            'default'     => 'html',
            'class'       => 'dkplus_shippingcost_option wc-enhanced-select',
            'options'     => $shippingCostOptions,
            'desc_tip'    => true,
        );
        return $form_fields;
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

        $gateways = WC()->payment_gateways->payment_gateways();
        foreach($gateways as $key => $gateway) {
            add_filter( 'woocommerce_settings_api_form_fields_' . $key, [$this, 'payment_form_fields']);
        }

        $shipping_methods = WC()->shipping->get_shipping_methods();
        foreach($shipping_methods as $key => $method) {
            add_filter( 'woocommerce_shipping_instance_form_fields_' . $key, [$this, 'shipping_form_fields']);
        }

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

            Logs::writeLog(Main::$module_slug . '/admin_notice', [
                'status' => 'success',
                'message' => 'The product has been successfully synced, the page will be refreshed now',
            ]);

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
