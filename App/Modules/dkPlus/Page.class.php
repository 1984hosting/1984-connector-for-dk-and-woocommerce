<?php

namespace woo_bookkeeping\App\Modules\dkPlus;


class Page extends \woo_bookkeeping\App\Core\Page
{
    function __construct()
    {
        $this->registerActions();
    }


    /**
     * Add a custom product data tab
     */
    public function product_tab_create( $default_tabs ) {
        $default_tabs['custom_tab'] = array(
            'label'   =>  __('dkPlus synchronization', PLUGIN_SLUG),
            'target'  =>  'product_tab_content',
            'priority' => 25,
            'class'   => array()
        );
        return $default_tabs;
    }

    public function product_tab_content() {
        include_once PLUGIN_TPL_DIR . '/dkPlus/product-tab.php';
    }



    protected function registerActions()
    {
        add_filter( 'woocommerce_product_data_tabs', [$this, 'product_tab_create'], 10, 1 );
        add_action( 'woocommerce_product_data_panels', [$this, 'product_tab_content'] );
    }
}