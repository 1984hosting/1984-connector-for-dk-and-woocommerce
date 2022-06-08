<?php

namespace woo_bookkeeping\Modules\dkPlus;

class Ajax
{
    private array $settings;

    public function __construct()
    {
        if (!current_user_can('manage_options') || empty($_POST['data'])) return;

        $this->settings = get_option(PLUGIN_SLUG);
        $this->registerActions();
    }

    public function syncProductsAll()
    {
        $dkPlus = new dkPlus($this->settings['dkPlus']);

        $params = [];
        parse_str($_POST['data'], $params);

        $dkPlus->productSyncAll(array_keys($params));
    }

    /*public function sync_product_one()
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
        add_action('admin_action_dkPlus_sync_products_all', [$this, 'sync_products_all']);
    }

}