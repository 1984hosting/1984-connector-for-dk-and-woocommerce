<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\WP_Notice;

class Main
{
    use API;

    public static string $module_slug = 'dkPlus';

    public function __construct(array $data)
    {
        if (empty($data[self::$module_slug]['login']) || empty($data[self::$module_slug]['password'])) {
            new WP_Notice('error', 'Error: Please, check the correctness of the login and password.');
            return;
        }

        self::$dkPlus = $data;

        $this->getToken();
        $this->LoadModules();
    }

    private function LoadModules()
    {
        new Ajax();
        new Page();
    }


}

