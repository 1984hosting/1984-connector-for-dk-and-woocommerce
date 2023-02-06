<?php

namespace woocoo;

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

    public static function loadFile($file): bool
    {
        $file = DIRECTORY_SEPARATOR == '\\' ? $file : str_replace('\\', DIRECTORY_SEPARATOR, $file);

        if (file_exists($file)) {
            require_once $file;
            return true;
        }

        return false;
    }

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

    public function loadInterface($interface): bool
    {
        $filename = $this->folder . DIRECTORY_SEPARATOR . $interface . '.interface.php';
        return self::loadFile($filename);
    }

    public function loadTrait($trait): bool
    {
        $filename = $this->folder . DIRECTORY_SEPARATOR . $trait . '.trait.php';
        return self::loadFile($filename);
    }
}