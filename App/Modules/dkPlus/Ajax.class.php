<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

class Ajax extends Main
{
    public function __construct()
    {

        $this->registerActions();
    }

    public function syncProductsAll()
    {
        $params = [];
        parse_str($_POST['data'], $params);

        $product = new Product();
        $product->productSyncAll(array_keys($params));
    }

    /*public function syncProductsOne()
    {
        $dkPlus = new dkPlus($settings_option['dkPlus']);

        $params = [];
        parse_str($_POST['data'], $params);

        $dkPlus->productSyncOne();
    }*/

    /**
     * Ajax actions
     * dkPlus_sync_products_all
     */
    private function registerActions()
    {
        add_action('admin_action_dkPlus_sync_products_all', [$this, 'syncProductsAll']);
    }

}