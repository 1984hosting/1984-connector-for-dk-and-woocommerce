<?php

namespace woo_bookkeeping\App\Core;

class Main
{
    private array $settings;

    public function __construct()
    {
        $settings = get_option(PLUGIN_SLUG);

        $this->settings = $settings;
        $this->registerActions();
    }

    public function addAdminPages()
    {
        include_once WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . PLUGIN_SLUG . '/templates/dkPlus/settings.php';

    }

    public function addAdminMenus()
    {
        add_menu_page(
            PLUGIN_NAME,
            PLUGIN_NAME,
            'manage_options',
            PLUGIN_SLUG,
            //[self::class, 'addAdminPages'],
            [$this, 'addAdminPages'],
            'dashicons-buddicons-activity',
            20,
        );
    }

    public function registerSettings()
    {
        register_setting(PLUGIN_SLUG, PLUGIN_SLUG);

        add_settings_section('dkPlus', '', '', PLUGIN_SLUG);

        add_settings_field(
            'dkPlus_login',
            'Login',
            [$this, 'settings_fields_format'],
            PLUGIN_SLUG,
            'dkPlus',
            [
                'label_for' => 'dkPlus_login',
                'name' => PLUGIN_SLUG . '[dkPlus][login]',
                'type' => 'text',
                'value' => !empty($this->settings['dkPlus']['login']) ? $this->settings['dkPlus']['login'] : '',
                'placeholder' => 'Login',
            ]
        );
        add_settings_field(
            'dkPlus_password',
            'Password',
            [$this, 'settings_fields_format'],
            PLUGIN_SLUG,
            'dkPlus',
            [
                'label_for' => 'dkPlus_password',
                'name' => PLUGIN_SLUG . '[dkPlus][password]',
                'type' => 'password',
                'value' => !empty($this->settings['dkPlus']['password']) ? $this->settings['dkPlus']['password'] : '',
                'placeholder' => 'Password',
            ]
        );


    }

    public static function settings_fields_format($args)
    {
        $type = !empty($args['type']) ? $args['type'] : '';
        $id = !empty($args['label_for']) ? $args['label_for'] : '';
        $name = !empty($args['name']) ? $args['name'] : $id;
        $value = !empty($args['value']) ? $args['value'] : 'text';
        $placeholder = !empty($args['placeholder']) ? $args['placeholder'] : '';

        printf(
            '<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" placeholder="%5$s" />',
            esc_attr($type),
            esc_attr($id),
            esc_attr($name),
            esc_attr($value),
            esc_attr($placeholder),
        );
    }


    public function WPInit()
    {
    }

    public function WPAdminInit()
    {
        self::registerSettings();
    }

    public function EnqueueScripts()
    {
        wp_enqueue_style(PLUGIN_SLUG . '_main', PLUGIN_URL . 'assets/css/main.css');

        wp_enqueue_script(PLUGIN_SLUG . '_main', PLUGIN_URL . 'assets/js/main.js');

        wp_localize_script(PLUGIN_SLUG . '_main', 'ajax', [
            'url' => esc_url(admin_url('admin.php')),
        ]); //use ajax.url from requests
    }

    protected function registerActions()
    {
        add_action('init', [$this, 'WPInit']);
        add_action('admin_menu', [$this, 'addAdminMenus'], 25);
        add_action('admin_init', [$this, 'WPAdminInit'], 25);
        add_action('admin_enqueue_scripts', [$this, 'EnqueueScripts'], 99);
        //add_action('after_setup_theme', [$this, 'load_text_domain']);
    }
}
