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
            new WP_Notice('error', 'Error: Please, check the correctness of the login and password.');
            return;
        }
//print_r($settings);
        $this->getToken();
        $this->LoadModules();
    }

    private function LoadModules()
    {
        new Ajax();
        new Page();
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

