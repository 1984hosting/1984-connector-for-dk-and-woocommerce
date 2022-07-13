<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

//use woo_bookkeeping\App\Core\Product;
//use woo_bookkeeping\App\Core\Woo_Query;
use woo_bookkeeping\App\Core\WP_Notice;

class Main extends \woo_bookkeeping\App\Core\Main
{
    use API;

    public static ?string $module_slug;

    public function __construct()
    {
        $settings = self::getInstance();
        static::$module_slug = self::getModuleSlug();

        if (empty($settings[static::$module_slug]['login']) || empty($settings[static::$module_slug]['password'])) {
            if (!isset($_GET['page']) || $_GET['page'] !== PLUGIN_SLUG) return;

            new WP_Notice('error', 'Error: Please, check the correctness of the login and password.');
            return;
        }

        $this->getToken();
        $this->LoadModules();
    }

    private function LoadModules()
    {
        new Page();
        Product::registerActions();
        Events::register_cron_events();
    }

    public static function getModuleSlug(): ?string
    {
        static $basename = null;
        if (NULL === $basename) {

            $basename = basename(dirname(__FILE__));
        }

        return $basename;
    }
}

