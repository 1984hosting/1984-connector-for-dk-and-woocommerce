<?php

namespace woo_bookkeeping\App\Core;

class Page
{
    /**
     * Variable tpl name from plugin_uri/templates/
     * @var string $tpl_name
     */
    public static string $tpl_name;

    public function __construct()
    {

        self::$tpl_name = 'settings';

        $this->addContentPlugin();
        $this->registerActions();
    }

    public function addContentPlugin()
    {
        add_action(PLUGIN_SLUG . '_content', function () {
            include_once PLUGIN_TPL_DIR . '/' . static::$tpl_name . '.php'; //TODO: add exception check file
        }, 10);
    }

    public function addAdminMenu()
    {
        add_menu_page(
            PLUGIN_NAME,
            PLUGIN_NAME,
            'manage_options',
            PLUGIN_SLUG,
            [$this, 'createPluginPage'],
            'dashicons-buddicons-activity',
            20,
        );
    }

    public function createPluginPage()
    {
        $actions = [
            'wrap_start',
            'before_content',
            'content',
            'after_content',
            'wrap_end',
        ];

        foreach ($actions as $action) {
            do_action(PLUGIN_SLUG . '_' . $action);
        }
    }

    /**
     * Saving Options and Installing a Cron Job
     */
    public static function saveDataAccount()
    {
        //$settings = Main::getInstance();
        $settings = get_option(PLUGIN_SLUG);

        foreach ($_POST[PLUGIN_SLUG] as $key => $account_data) {
            if (empty($settings[$key])) {
                $settings[$key] = $account_data;
                continue;
            }

            unset($settings[$key]['token']);
            foreach ($account_data as $k => $v) {
                $settings[$key][$k] = $v;
            }
        }
        update_option(PLUGIN_SLUG, $settings, 'no');

        Main::LoadModules();
        return true;
    }

    private function registerActions()
    {
        add_action('admin_menu', [$this, 'addAdminMenu'], 25);

        new \woo_bookkeeping\App\Core\Ajax('woo_save_account', function () {
            self::saveDataAccount();
            Logs::removeLogs();

            AJAX::response([
                'status' => 1,
                'message' => 'Account data success updated',
            ]);
        });

        new \woo_bookkeeping\App\Core\Ajax('woo_get_token', function () {
            AJAX::response([
                'status' => 1,
                'message' => 'Account data success updated',
            ]);
        });
    }
}