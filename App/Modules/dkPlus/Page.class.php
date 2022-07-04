<?php

namespace woo_bookkeeping\App\Modules\dkPlus;


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

        echo 'hello';

    }


    /**
     * Register required actions
     */
    protected function registerActions()
    {
        add_filter('woocommerce_product_data_tabs', [$this, 'product_tab_create'], 10, 1);
        add_action('woocommerce_product_data_panels', [$this, 'product_tab_content']);
        add_action('add_meta_boxes', [$this, 'create_meta_box']);
    }
}