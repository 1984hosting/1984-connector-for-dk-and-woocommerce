<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

class Ajax extends Main
{
    private int $product_id;
    private array $params;
    private string $button_type;
    private string $woocoo_schedule;

    public function __construct()
    {
        $this->registerActions();
    }

    /**
     * Getting parameters passed in ajax request
     */
    private function getParams(): void
    {
        $params = [];
        parse_str($_POST['data'], $params);

        if (isset($params['product_id'])) {
            $this->product_id = $params['product_id'];
        }
        if (isset($params['type'])) {
            $this->button_type = $params['type'];
        }
        if (isset($params['woocoo_schedule'])) {
            $this->woocoo_schedule = $params['woocoo_schedule'];
        }

        unset($params['action'], $params['product_id'], $params['type'], $params['woocoo_schedule']);

        $this->params = array_keys($params);
    }

    /**
     * Saving sync settings
     */
    public function syncSave()
    {
        $this->getParams();

        //if selected button save and sync (additional synchronization is performed)
        if ($this->button_type === 'save_and_sync') {
            Product::productSyncAll($this->params);
        }

        $this->saveOptions();
    }

    /**
     * Saving Options and Installing a Cron Job
     */
    private function saveOptions()
    {
        $settings = Main::getInstance();

        $settings[static::$module_slug]['schedule']['params'] = $this->params;

        $task_name = 'woocoo_update_products_' . static::$module_slug;

        wp_clear_scheduled_hook($task_name); //remove old event

        if (isset($this->woocoo_schedule) && $this->woocoo_schedule !== 'disabled' && $settings[static::$module_slug]['token']) {
            $settings[static::$module_slug]['schedule']['name'] = $this->woocoo_schedule;

            wp_schedule_event(time(), $this->woocoo_schedule, $task_name);
        }

        update_option(PLUGIN_SLUG, $settings, 'no');
    }

    /**
     * Synchronization of a single product
     */
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
        add_action('admin_action_dkPlus_save_sync', [$this, 'syncSave']);
        add_action('admin_action_dkPlus_sync_products_one', [$this, 'syncProductsOne']);
    }
}