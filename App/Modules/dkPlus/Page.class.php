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

        unset($settings[Main::$module_slug]['schedule']['name']);

        $task_name = 'woocoo_update_products_' . Main::$module_slug;

        wp_clear_scheduled_hook($task_name); //remove old event

        if (isset($data['woocoo_schedule']) && $data['woocoo_schedule'] !== 'disabled' && $settings[Main::$module_slug]['token']) {
            $settings[Main::$module_slug]['schedule']['name'] = $data['woocoo_schedule'];

            wp_schedule_event(time(), $data['woocoo_schedule'], $task_name);
        }

        return update_option(PLUGIN_SLUG, $settings, 'no');
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
    protected function registerActions()
    {
        add_filter('woocommerce_product_data_tabs', [$this, 'product_tab_create'], 10, 1);
        add_action('woocommerce_product_data_panels', [$this, 'product_tab_content']);
        add_action('add_meta_boxes', [$this, 'create_meta_box']);

        /** Ajax actions */
        new Ajax(Main::$module_slug . '_save', function () {
            Page::saveOptions();

            AJAX::response([
                'status' => 1,
                'message' => 'Settings saved successfully',
            ]);
        });
        new Ajax(Main::$module_slug . '_sync_and_save', function () {
            Product::productSyncAll();

            Page::saveOptions();

            AJAX::response([
                'status' => 1,
                'message' => 'Saved settings and synced successfully',
            ]);
        });
        new Ajax(Main::$module_slug . '_sync_prolong', function () {
            $response = Product::productProlongSync();

            Page::saveOptions();

            AJAX::response($response);
        });
        new Ajax(Main::$module_slug . '_sync_product_one', function () {
            Product::productSyncOne();

            AJAX::response([
                'status' => 1,
                'message' => 'The product has been successfully synced, the page will be refreshed now',
            ]);
        });
        new Ajax(Main::$module_slug . '_send_to', function () {
            Product::productSend();

            AJAX::response([
                'status' => 1,
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
    }
}