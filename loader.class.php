<?php
/**
 * The file that defines the loader class
 *
 * A class definition that includes attributes and functions of the loader class
 *
 * @since      0.1
 *
 * @package    WooCoo
 * @subpackage WooCoo
 */

namespace woocoo;

/**
 * Class loader
 */
class loader
{

    public string $folder = '';

    private string $prefix = '';

    /**
     * @param string $folder
     * @param string $prefix
     */
    public function __construct(string $folder = '', string $prefix = '')
    {
        $this->folder = $folder;
        $this->prefix = $prefix;

        spl_autoload_register([
            $this,
            'loader'
        ]);
    }

    /**
     * Load File
     *
     * @param $file
     * @return bool
     */
    public static function loadFile($file): bool
    {
        $file = DIRECTORY_SEPARATOR == '\\' ? $file : str_replace('\\', DIRECTORY_SEPARATOR, $file);

        if (file_exists($file)) {
            require_once $file;
            return true;
        }

        return false;
    }

    /**
     * Loader
     *
     * @param $item
     * @return void
     */
    public function loader($item)
    {
        if ($this->prefix) {
            $_item = str_replace($this->prefix, '', $item);

            if ($item === $_item) {
                return;
            }

            $item = $_item;
        }

        $this->loadClass($item) || $this->loadInterface($item) || $this->loadTrait($item) || var_dump($item);
    }

    /**
     * Load class
     *
     * @param $classname
     * @return bool
     */
    public function loadClass($classname): bool
    {
        $filename = $this->folder . DIRECTORY_SEPARATOR . $classname . '.class.php';

        if (self::loadFile($filename)) {
            $fullName = $this->prefix . $classname;
            if (is_subclass_of($fullName, 'woocoo\__init')) {
                $fullName::__init();
            }
            return true;
        }

        return false;
    }

    /**
     * Load Interface
     *
     * @param $interface
     * @return bool
     */
    public function loadInterface($interface): bool
    {
        $filename = $this->folder . DIRECTORY_SEPARATOR . $interface . '.interface.php';
        return self::loadFile($filename);
    }

    /**
     * Load Trait
     *
     * @param $trait
     * @return bool
     */
    public function loadTrait($trait): bool
    {
        $filename = $this->folder . DIRECTORY_SEPARATOR . $trait . '.trait.php';
        return self::loadFile($filename);
    }
}