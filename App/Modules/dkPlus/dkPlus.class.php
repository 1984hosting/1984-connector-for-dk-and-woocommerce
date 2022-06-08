<?php

namespace woo_bookkeeping\Modules\dkPlus;

use woo_bookkeeping\App\Core\WP_Notice;

class dkPlus
{
    use API;

    public function __construct(array $data)
    {
        if (empty($data['login']) || empty($data['password'])) {
            new WP_Notice('error', 'Error: Please, check the correctness of the login and password.');
        }

        self::$dkPlus = $data;
        $this->getToken();
        var_dump(self::$token);
    }


}

