<?php

namespace woo_bookkeeping\App\Core;

use woo_bookkeeping\App\Modules\dkPlus\Main as dkPlus;

class Main
{
    public static array $settings = [];

    /**
     * collection actions
     * @var array $actions
     */
    public static array $actions = [];

    /**
     * collection styles
     * @var array $styles
     */
    public static array $styles = [];

    /**
     * collection scripts
     * @var array $scripts
     */
    public static array $scripts = [];


    /**
     * Returns the *Main* instance of this class.
     *
     * @staticvar Main $instance The *Main* instances of this class.
     *
     * @return Main The *Main* instance.
     */
    public static function getInstance()
    {
        static $settings = null;
        if (NULL === $settings) {
            $settings = get_option(PLUGIN_SLUG);
        }

        return $settings;
    }


    public static function LoadCore()
    {
        self::$styles = [
            'main',
        ];
        self::$scripts = [
            'main',
        ];

        if (isset($_GET['page']) && $_GET['page'] === PLUGIN_SLUG) {
            self::$scripts[] = 'woocoo_sync';
        }

        $main = new Main();
        $main->LoadModules();
        $main->registerActions();
        Main::cronLoader();
    }

    private function LoadModules()
    {
        new dkPlus(self::getInstance());
        new Page();
    }

    public static function cronLoader()
    {
        CronSchedule::registerActions();
        dkPlus::cronActions();
    }

    public function EnqueueScripts(): void
    {
        /** For UI page settings */
        wp_enqueue_script('jquery-ui-tabs');

        if (!empty(self::$styles)) {
            foreach (self::$styles as $style) {
                wp_enqueue_style(PLUGIN_SLUG . '_' . $style, PLUGIN_URL . 'templates/assets/css/' . $style . '.css', [], time());
            }
        }

        if (empty(self::$scripts)) return;

        foreach (self::$scripts as $script) {
            if (filter_var($script, FILTER_VALIDATE_URL) === FALSE) {
                $script_name = $script;
                $script_uri = PLUGIN_URL . 'templates/assets/js/' . $script . '.js';
            } else {
                $script_name = pathinfo($script, PATHINFO_FILENAME);
                $script_uri = $script;
            }

            wp_enqueue_script(PLUGIN_SLUG . '_' . $script, $script_uri, [], time(), true);
        }

        wp_localize_script(PLUGIN_SLUG . '_main', 'ajax', [
            'url' => esc_url(admin_url('admin.php')),
        ]); //use ajax.url from requests
    }

    protected function registerActions()
    {
        add_action('admin_enqueue_scripts', [$this, 'EnqueueScripts'], 99);
    }

    protected function __construct()
    {
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }
}