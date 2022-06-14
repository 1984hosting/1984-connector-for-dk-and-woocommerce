<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\WP_Notice;

class Main extends \woo_bookkeeping\App\Core\Main
{
    use API;

    public static string $module_slug = 'dkPlus';

    public function __construct()
    {
        $settings = self::getInstance();
        $module_slug = self::getModuleSlug();

        if (empty($settings[$module_slug]['login']) || empty($settings[$module_slug]['password'])) {
            new WP_Notice('error', 'Error: Please, check the correctness of the login and password.');
            return;
        }

        $this->getToken();
        $this->LoadModules();
    }

    public static function getModuleSlug(): ?string
    {
        static $basename = null;
        if (NULL === $basename) {

            $basename = basename(dirname(__FILE__));
        }

        return $basename;
    }

    private function LoadModules()
    {
        new Ajax();
        new Page();
    }


}

