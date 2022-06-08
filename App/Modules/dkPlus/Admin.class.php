<?php

namespace woo_bookkeeping\Modules\dkPlus;


class Admin
{
    function __construct()
    {
        $this->registerActions();
    }


    /**
     * Add a custom product data tab
     */
    public function create_tab_product($tabs)
    {
        $tabs['tab_dk_sync'] = [
            'title' => __('dkPlus synchronization', PLUGIN_SLUG),
            'priority' => 50,
            'callback' => [$this, 'tab_content']
        ];

        return $tabs;
    }

    public function tab_content()
    {
        echo '<h2>New Product Tab</h2>';
    }

    protected function registerActions()
    {
        add_filter('woocommerce_product_data_tabs', [$this, 'create_tab_product'], 98);
    }
}