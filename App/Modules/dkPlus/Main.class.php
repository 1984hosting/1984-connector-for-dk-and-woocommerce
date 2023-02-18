<?php
/**
 * The file that defines the Main class
 *
 * A class definition that includes attributes and functions of the Main class
 *
 * @since      0.1
 *
 * @package    WooCoo
 * @subpackage WooCoo/App/Modules/dkPlus
 */

namespace woocoo\App\Modules\dkPlus;

use woocoo\App\Core\WP_Notice;

/**
 * Class Main
 */
class Main extends \woocoo\App\Core\Main
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

        /** Load Modules */
        new Page();
        new Events();
        new Product();
    }

    /**
     * Get Module Slug
     *
     * @return string|null
     */
    public static function getModuleSlug(): ?string
    {
        static $basename = null;
        if (NULL === $basename) {
            $basename = basename(dirname(__FILE__));
        }

        return $basename;
    }
}
