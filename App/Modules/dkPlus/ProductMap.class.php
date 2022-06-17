<?php


namespace woo_bookkeeping\App\Modules\dkPlus;


use woo_bookkeeping\App\Core\ProductMapper;

class ProductMap extends ProductMapper
{
    const Map = [
        /*'ItemCode' => [
            'field' => 'sku',
        ],*/
        'Description' => [//update post
            'field' => 'set_description',
        ],
        'RecordModified' => [
            'field' => 'set_date_modified',
        ],
        'UnitPrice1' => [
            'field' => 'set_price',
        ],
        'UnitQuantity' => [
            'field' => 'set_stock_quantity',
        ],
    ];
}