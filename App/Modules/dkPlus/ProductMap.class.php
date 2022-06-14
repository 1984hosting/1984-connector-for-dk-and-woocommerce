<?php


namespace woo_bookkeeping\App\Modules\dkPlus;


use woo_bookkeeping\App\Core\ProductMapper;

class ProductMap extends ProductMapper
{
    const Map = [
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
        ],
        'UnitQuantity' => [//TotalQuantityInWarehouse todo: waiting for a response from the customer
            'field' => 'stock_quantity',
            'table' => 'wc_product_meta_lookup',
            'tag_id' => 'product_id',
        ],
    ];
}