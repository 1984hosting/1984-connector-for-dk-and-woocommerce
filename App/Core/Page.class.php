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
        add_action(PLUGIN_SLUG . '_content', function() {
            include_once PLUGIN_TPL_DIR . '/' . self::$tpl_name . '.php';
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

    public function registerSettings()
    {
        $settings = Main::getInstance();

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
                'value' => !empty($settings['dkPlus']['login']) ? $settings['dkPlus']['login'] : '',
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
                'value' => !empty($settings['dkPlus']['password']) ? $settings['dkPlus']['password'] : '',
                'placeholder' => 'Password',
            ]
        );


    }

    public function settings_fields_format($args)
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

    protected function registerActions()
    {
        add_action('admin_menu', [$this, 'addAdminMenu'], 25);
        add_action('admin_init', [$this, 'registerSettings'], 25);
    }
}