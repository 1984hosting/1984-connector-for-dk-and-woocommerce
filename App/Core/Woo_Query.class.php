<?php
/**
 * The file that defines the Woo_Query class
 *
 * A class definition that includes attributes and functions of the Woo_Query class
 *
 * @since      0.1
 *
 * @package    WooCoo
 * @subpackage WooCoo/App/Core
 */

namespace woocoo\App\Core;

/**
 * Class Woo_Query
 */
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
        if (NULL === $instance) {
            global $wpdb;
            $instance = &$wpdb;
        }

        return $instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Woo_Query* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Woo_Query* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Woo_Query*
     * instance.
     *
     * @return void
     */
    public function __wakeup()
    {
    }

    /**
     * Get all products from woocommerce
     *
     * @param string $fields
     * @return array
     */
    public static function getProducts(string $fields): array
    {
        $table = 'wc_product_meta_lookup';

        return self::getInstance()->get_results('SELECT ' . $fields . ' FROM `' . self::getInstance()->prefix . $table . '` WHERE `sku` != ""', ARRAY_A);
    }

    /**
     * Product search in woocommerce
     *
     * @param string $fields
     * @param int $product_id
     * @return array
     */
    public static function getProduct(string $fields, int $product_id): array
    {
        $table = 'wc_product_meta_lookup';

        return self::getInstance()->get_results('SELECT ' . $fields . ' FROM `' . self::getInstance()->prefix . $table . '` WHERE `product_id` = ' . $product_id . ' LIMIT 1', ARRAY_A)[0];
    }

    /**
     * Get All Products SKU
     *
     * @return array|false
     */
    public static function getAllProductsSKU()
    {
        $results = self::getInstance()->get_results('SELECT meta_value FROM `' . self::getInstance()->prefix . 'postmeta` WHERE `meta_key` = "_sku"', ARRAY_A);
        return !empty($results) ? array_column($results, 'meta_value') : false;
    }

    /**
     * Get Children
     *
     * @param $product_id
     * @return array|false
     */
    public static function getChildren($product_id)
    {
        $product = wc_get_product($product_id);
        $product_children = $product->get_children();

        if (empty($product_children)) return false;

        return $product_children;
    }

    /**
     * Create
     *
     * @param string $table
     * @param array $data
     * @return bool|int
     */
    private function create(string $table, array $data)
    {
        return self::getInstance()->insert(self::getInstance()->prefix . $table, $data);
    }

    /**
     * Update
     *
     * @param array $fields
     * @param string $table
     * @param array $where
     * @return bool|int
     */
    private function update(array $fields, string $table, array $where)
    {
        return self::getInstance()->update(self::getInstance()->prefix . $table, $fields, $where);
    }

    /**
     * Delete
     *
     * @param array $table
     * @param array $where
     * @return bool|int
     */
    private function delete(array $table, array $where)
    {
        return self::getInstance()->delete(self::getInstance()->prefix . $table, $where);
    }
}
