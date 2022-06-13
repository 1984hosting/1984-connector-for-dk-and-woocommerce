<?php
namespace woo_bookkeeping\App\Core;

class Woo_Query
{

    /**
     * Returns the *Woo_Query* instance of this class.
     *
     * @staticvar Woo_Query $instance The *Woo_Query* instances of this class.
     *
     * @return Woo_Query The *Woo_Query* instance.
     */
    public static function getInstance()
    {
        static $instance = null;
        if ( NULL === $instance)
        {
            global $wpdb;
            $instance = &$wpdb;
        }

        return $instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Woo_Query* via the `new` operator from outside of this class.
     */
    protected function __construct() { }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Woo_Query* instance.
     *
     * @return void
     */
    private function __clone() { }

    /**
     * Private unserialize method to prevent unserializing of the *Woo_Query*
     * instance.
     *
     * @return void
     */
    //private function __wakeup() { }


    /**
     * @param string $fields
     * @return array|\stdClass[]
     */
    public function getProducts(string $fields): array
    {
        $table = 'wc_product_meta_lookup';

        return self::getInstance()->get_results('SELECT ' . $fields . ' FROM `' . self::getInstance()->prefix . $table . '`', ARRAY_A);
    }

    /**
     * @param string $fields
     * @return array|\stdClass[]
     */
    public function getProduct(string $fields, int $product_id): array
    {
        $table = 'wc_product_meta_lookup';

        return self::getInstance()->get_results('SELECT ' . $fields . ' FROM `' . self::getInstance()->prefix . $table . '` WHERE `product_id` = ' . $product_id . ' LIMIT 1', ARRAY_A);
    }

    /**
     * @param string $table
     * @param array $data
     * @return bool|int|\mysqli_result|resource|null
     */
    public function create(string $table, array $data)
    {
        return self::getInstance()->insert( self::getInstance()->prefix . $table, $data);
    }

    /**
     * @param array $fields
     * @param string $table
     * @param array $where
     * @return bool|int|\mysqli_result|resource|null
     */
    public function update(array $fields, string $table, array $where)
    {
        return self::getInstance()->update(self::getInstance()->prefix . $table, $fields, $where);
    }

    /**
     * @param array $table
     * @param array $where
     * @return bool|int|\mysqli_result|resource|null
     */
    public function delete(array $table, array $where)
    {
        return self::getInstance()->delete(self::getInstance()->prefix . $table, $where);
    }
}
