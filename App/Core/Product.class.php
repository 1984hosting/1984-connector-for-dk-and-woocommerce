<?php
/**
 * The file that defines the Product class
 *
 * A class definition that includes attributes and functions of the Product class
 *
 * @since      0.1
 *
 * @package    WooCoo
 * @subpackage WooCoo/App/Core
 */

namespace woocoo\App\Core;

use woocoo\App\Modules\dkPlus\ProductMap;

/**
 * Class Product
 */
abstract class Product extends Woo_Query
{
    //abstract protected static function productSyncOne(array $needed_fields, $product_id);

    //abstract protected static function productSyncAll(array $needed_fields);

    /**
     * Search for a product in the resulting array (from remote services) of all products
     *
     * @param string $product_sku Product sku
     * @param array $products The resulting array products
     * @return array Product data
     */
    public static function searchProductArray(string $product_sku, array $products): array
    {
        $found_key = array_search($product_sku, array_column($products, 'sku'));

        if ($found_key === false) return [];

        return $products[$found_key];
    }

    /**
     * Search for variable product in the resulting array (from remote services) of all products
     *
     * @param string $product_sku Product sku
     * @param array $products The resulting array products
     * @return array Product data
     */
    public static function searchProductVariation(string $product_sku, array $products): array
    {
        foreach ($products as $product) {
            if (!empty($product['children'])) {
                $found_key = array_search($product_sku, array_column($product['children'], 'ItemCode'));
                if ($found_key === false) break;
                return $product;
            }
        }
        return [];
    }

    /**
     * Create an Attribute For Variable Product
     *
     * @param $product
     * @param $variations
     * @return void
     */
    public static function createAttribute( $product, $variations ) {
        $attribute = new \WC_Product_Attribute();
        $attribute->set_name( 'Alternative' );
        $attribute->set_visible( true );
        $attribute->set_variation( true );
        $options = [];
        foreach($variations as $variation) {
            $options[] = $variation["ItemCode"];
        }
        $attribute->set_options($options);
        $product->set_attributes( array($attribute) );
        $product->save();

    }

    /**
     * Create a Variation For Variable Product
     *
     * @param $product
     * @param $product_variation
     * @return void
     * @throws \WC_Data_Exception
     */
    public static function createVariation( $product, $product_variation ) {

        $variation = new \WC_Product_Variation();
        $variation->set_parent_id( $product->get_id() );
        $variation->set_name( $product_variation['name'] );
        $variation->set_sku( $product_variation['sku'] );
        $variation->set_description( $product_variation['description'] );
        $variation->set_stock_quantity( $product_variation['stock_quantity'] );
        $variation->set_manage_stock( $product_variation['manage_stock'] );
        $variation->set_regular_price( $product_variation['regular_price'] );

        if (isset($product_variation['tax'])) {
            if (($product_variation['tax'] > 0)) {
                $variation->set_tax_status( 'taxable' );
            } else {
                $variation->set_tax_status( 'none' );
            }
        }
        $variation->set_attributes( array( 'alternative' => $product_variation['sku'] ) );
        $variation->save();

    }

    /**
     * Add a Product
     *
     * @param array $needed_fields
     * @param $product
     * @param $import_products
     * @return int
     * @throws \WC_Data_Exception
     */
    public static function productAdd(array $needed_fields, $product, $import_products): int
    {

        if (empty($product['children'])) {
            if(self::searchProductVariation($product['sku'], $import_products)) {
                return 0;
            }
            $wc_product = new \WC_Product();
        } else {
            $wc_product = new \WC_Product_Variable();
            self::createAttribute( $wc_product, $product['children'] );
            foreach ($product['children'] as $child) {
                if($product_variation = self::searchProductArray($child["ItemCode"], $import_products)) {
                    self::createVariation($wc_product, $product_variation);
                }
            }
        }

        $functions = array_combine(static::dataFormatSet($needed_fields), $needed_fields);

        call_user_func([$wc_product, 'set_name'], $product['name']);
        call_user_func([$wc_product, 'set_sku'], $product['sku']);

        foreach ($functions as $key => $value) {
            call_user_func([$wc_product, $key], $product[$value]);
        }

        return $wc_product->save();
    }

    /**
     * Product update in woocommerce
     *
     * @param array $needed_fields
     * @param $product_id
     * @param $product
     * @return bool
     * @throws \WC_Data_Exception
     */
    public static function productUpdate(array $needed_fields, $product_id, $product): bool
    {
        if (empty($product) || empty($product_id)) return true;

        $wc_product = wc_get_product($product_id);
        if(!$wc_product){
            return false;
        }

        $functions = array_combine(static::dataFormatSet($needed_fields), $needed_fields);

        foreach ($functions as $key => $value) {
            call_user_func([$wc_product, $key], $product[$value]);
        }

        if (isset($product['tax'])) {
            if (($product['tax'] > 0)) {
                $wc_product->set_tax_status( 'taxable' );
            } else {
                $wc_product->set_tax_status( 'none' );
            }
        }

        if (isset($product['visibility'])) {
            if (($product['visibility'] > 0)) {
                $wc_product->set_catalog_visibility('visible');
            } else {
                $wc_product->set_catalog_visibility('hidden');
            }
        }

        $wc_product->save();

        return true;
    }

    /**
     * Get product data from woocommerce
     *
     * @param array $needed_fields
     * @param $product_id
     * @return array
     */
    public static function productGet(array $needed_fields, $product_id): array
    {
        $wc_product = new \WC_Product($product_id);

        /** @var $get_functions returns functions to query product fields */
        $get_functions = static::dataFormatGet($needed_fields);

        /** @var $needed_fields fields format dkPlus */
        $needed_map = ProductMap::ProductMapReverse($needed_fields);

        $product = [];
        foreach ($get_functions as $key => $field) {
            $product[$needed_map[$key]] = call_user_func([$wc_product, $field]);
        }

        return $product;
    }

    /**
     * Variation update in woocommerce
     *
     * @param array $needed_fields
     * @param $variation_id
     * @param $product
     * @return bool
     */
    public static function variationUpdate(array $needed_fields, $variation_id, $product): bool
    {
        if (empty($product)) return true;

        $wc_variation = new \WC_Product_Variation($variation_id);

        $functions = array_combine(static::dataFormatSet($needed_fields), $needed_fields);

        foreach ($functions as $key => $value) {
            call_user_func([$wc_variation, $key], $product[$value]);
        }

        $wc_variation->save();

        return true;
    }

    /**
     * Get variation from woocommerce product
     *
     * @param array $needed_fields
     * @param $variation_id
     * @param $product
     * @return bool
     */
    public static function variationGet(array $needed_fields, $variation_id): array
    {
        $wc_product = new \WC_Product_Variation($variation_id);

        /** @var $functions returns functions to query product fields */
        $functions = static::dataFormatGet($needed_fields);

        /** @var $needed_fields fields format dkPlus */
        $needed_map = ProductMap::ProductMapReverse($needed_fields);

        $product = [];
        foreach ($functions as $key => $field) {
            $product[$needed_map[$key]] = call_user_func([$wc_product, $field]);
        }

        return $product;
    }

    /**
     * Set Data Format
     *
     * @param array $product_data
     * @return array
     */
    public static function dataFormatSet(array $product_data): array
    {
        foreach ($product_data as &$value) {
            $value = 'set_' . $value;
        }

        return $product_data;
    }

    /**
     * Get Data Format
     *
     * @param array $product_data
     * @return array
     */
    public static function dataFormatGet(array $product_data): array
    {
        foreach ($product_data as &$value) {
            $value = 'get_' . $value;
        }

        return $product_data;
    }
}
