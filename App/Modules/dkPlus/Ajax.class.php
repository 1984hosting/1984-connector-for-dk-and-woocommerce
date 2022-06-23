<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

class Ajax extends Main
{
    public int $product_id;
    public array $params;

    public function __construct()
    {
        $this->registerActions();
    }

    private function getParams(): void
    {
        $params = [];
        parse_str($_POST['data'], $params);

        if (isset($params['product_id'])) {
            $this->product_id = $params['product_id'];
            unset($params['product_id']);
        }

        unset($params['action']);

        $this->params = array_keys($params);
    }

    public function syncProductsAll()
    {
        $this->getParams();
        Product::productSyncAll($this->params);
    }

    public function syncProductsOne()
    {
        $this->getParams();
        echo json_encode(Product::productSyncOne($this->params, $this->product_id));
    }

    /**
     * Ajax actions
     * dkPlus_sync_products_all
     * dkPlus_sync_products_one
     */
    public function registerActions()
    {
        add_action('admin_action_dkPlus_sync_products_all', [$this, 'syncProductsAll']);
        add_action('admin_action_dkPlus_sync_products_one', [$this, 'syncProductsOne']);
    }

}