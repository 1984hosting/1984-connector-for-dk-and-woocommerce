<?php

namespace woo_bookkeeping\App\Core;

use woo_bookkeeping\App\Modules\dkPlus\Main as dkPlus;

class Main
{
    public static array $settings;

    /**
     * @var array $actions - collection actions
     */
    public static array $actions;

    /**
     * @var array $styles - collection styles
     */
    public static array $styles;

    /**
     * @var array $scripts - collection scripts
     *
     */
    public static array $scripts;


    public function __construct()
    {
        self::$settings = get_option(PLUGIN_SLUG);

        self::$styles = [
            'main',
        ];
        self::$scripts = [
            'main',
        ];

        $this->LoadModules();
        $this->registerActions();
    }

    private function LoadModules()
    {
        new dkPlus(self::$settings);
        new Page();
    }

    public function EnqueueScripts(): void
    {
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

            wp_enqueue_script(PLUGIN_SLUG . '_' . $style, $script_uri, [], time(), true);
        }

        wp_localize_script(PLUGIN_SLUG . '_main', 'ajax', [
            'url' => esc_url(admin_url('admin.php')),
        ]); //use ajax.url from requests
    }

    protected function registerActions()
    {
        add_action('admin_enqueue_scripts', [$this, 'EnqueueScripts'], 99);
    }
}