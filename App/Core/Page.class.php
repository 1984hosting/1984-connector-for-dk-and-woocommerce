<?php
/**
 * The file that defines the Page class
 *
 * A class definition that includes attributes and functions of the Page class
 *
 * @since      0.1
 *
 * @package    WooCoo
 * @subpackage WooCoo/App/Core
 */

namespace woocoo\App\Core;

/**
 * Class Page
 */
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

    /**
     * Add Content Plugin
     *
     * @return void
     */
    public function addContentPlugin()
    {
        add_action(PLUGIN_SLUG . '_content', function () {
            include_once PLUGIN_TPL_DIR . '/' . static::$tpl_name . '.php'; //TODO: add exception check file
        }, 10);
    }

    /**
     * Add Admin Menu
     *
     * @return void
     */
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

    /**
     * Create Plugin Page
     *
     * @return void
     */
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
     *
     * @return bool
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

    /**
     * Register Actions
     *
     * @return void
     */
    private function registerActions()
    {
        add_action('admin_menu', [$this, 'addAdminMenu'], 25);

        new \woocoo\App\Core\Ajax('woo_save_account', function () {
            self::saveDataAccount();
            Logs::removeLogs();

            AJAX::response([
                'status' => 1,
                'message' => __('Account data success updated', PLUGIN_SLUG),
            ]);
        });

        new \woocoo\App\Core\Ajax('woo_get_token', function () {
            AJAX::response([
                'status' => 1,
                'message' => __('Account data success updated', PLUGIN_SLUG),
            ]);
        });
    }
}