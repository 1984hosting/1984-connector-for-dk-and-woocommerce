<?php

namespace woo_bookkeeping\Modules\dkPlus;

class Product extends \woo_bookkeeping\App\Core\Woo_Query
{
    use API;

    public function productSyncOne(array $needed_fields, $product_id): bool
    {
        $product_sku = $this->getProduct('sku', $product_id)['sku'];
        $dkPlus_product = $this->productFetchOne($product_sku);

        if (empty($dkPlus_product)) return true;

        $product_data = $this->productData($product_id, $needed_fields, $dkPlus_product);

        if (empty($product_data)) {
            return true;
        }

        foreach (array_shift($product_data) as $table => $data) {
            $this->update($data['fields'], $table, $data['where']);
        }

        return true;
    }

    /**
     * @param array $needed_fields - needed fields from woocommerce
     * @return bool - result for sync
     */
    public function productSyncAll(array $needed_fields): bool
    {
        $dkPlus_products = $this->productFetchAll();

        if (empty($dkPlus_products)) return true;

        $woo_products = $this->getProducts('product_id, sku');

        foreach ($woo_products as $woo_product) {
            $product_id = (int)$woo_product['product_id'];
            $product_sku = $woo_product['sku'];

            /**
             * @var $found_key - found product key
             */
            $found_key = array_search($product_sku, array_column($dkPlus_products, 'ItemCode'));

            if (!$found_key) continue;

            $product_data = $this->productData($product_id, $needed_fields, $dkPlus_products[$found_key]);
        }

        if (empty($product_data)) {
            return true;
        }

        foreach ($product_data as $item) {
            foreach ($item as $table => $data) {
                $this->update($data['fields'], $table, $data['where']);
            }
        }

        return true;
    }

    /**
     * @param string $product_id - get product with dkPlus API
     * @return array
     */
    private function productFetchOne(string $product_id): array
    {
        $method = '/Product/' . $product_id; //:code
        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . self::$token,
                'Content-type' => 'application/x-www-form-urlencoded',
            ],
            'method' => 'GET',
        ];

        return $this->request($method, $args);
    }

    private function productFetchAll(): array
    {
        $method = '/Product';
        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . self::$token,
                'Content-type' => 'application/x-www-form-urlencoded',
            ],
            'method' => 'GET',
            'timeout' => 30,
        ];

        return $this->request($method, $args);
    }

    /**
     * @param int $product_id
     * @param array $needed_fields
     * @param array $dk_product
     * @return array|null
     */
    private function productData(int $product_id, array $needed_fields, array $dk_product): ?array
    {
        foreach ($needed_fields as $need_field) {

            $relations = $this->productFieldsRelations($need_field);

            if (!$relations) {
                continue;
            }

            $field = $relations['field'];
            $table = $relations['table'];
            $where_field = $relations['tag_id'];

            foreach ($dk_product as $key => $dk_product_field) {
                if ($key !== $need_field) {
                    continue;
                }

                if (isset($relations['callback'])) {
                    call_user_func([$this, $relations['callback']], $dk_product_field, $product_id);
                }

                $product_data[$product_id][$table]['fields'][$field] = $dk_product_field;

                if (isset($product_data[$product_id][$table]['where'][$where_field])) {
                    continue;
                }

                $product_data[$product_id][$table]['where'][$where_field] = $product_id;
            }
        }

        if (empty($product_query)) {
            return null;
        }

        return $product_query;
    }


    /**
     * @param string $field - dkPlus field
     * @return false|string[] - field data for woocommerce
     */
    private function productFieldsRelations(string $field)
    {
        $relations = [
            'Description' => [
                'field' => 'post_content',
                'table' => 'posts',
                'tag_id' => 'id',
            ],
            'RecordModified' => [
                'field' => 'post_modified',
                'table' => 'posts',
                'tag_id' => 'id',
            ],
            'UnitPrice1' => [//UnitPrice1WithTax todo: waiting for a response from the customer
                'field' => 'min_price',
                'table' => 'wc_product_meta_lookup',
                'tag_id' => 'product_id',
                'callback' => 'setPrice'
            ],
            'UnitQuantity' => [//TotalQuantityInWarehouse todo: waiting for a response from the customer
                'field' => 'stock_quantity',
                'table' => 'wc_product_meta_lookup',
                'tag_id' => 'product_id',
                'callback' => 'setQty',
            ],
        ];

        if (!empty($relations[$field])) {
            return $relations[$field];
        }

        return false;
    }

    /**
     * @param $price - new price
     * @param $post_id - post id
     */
    private function setPrice($price, $post_id)
    {
        $this->update([
            'meta_value' => $price,
        ], 'postmeta', [
            'post_id' => $post_id,
            'meta_key' => '_price'
        ]);
    }

    /**
     * @param $qty - new quantity
     * @param $post_id - post id
     */
    private function setQty($qty, $post_id)
    {
        $this->update([
            'meta_value' => $qty,
        ], 'postmeta', [
            'post_id' => $post_id,
            'meta_key' => '_stock'
        ]);
    }

}