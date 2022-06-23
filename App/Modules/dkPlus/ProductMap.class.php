<?php


namespace woo_bookkeeping\App\Modules\dkPlus;


use woo_bookkeeping\App\Core\ProductMapper;

class ProductMap extends ProductMapper
{
    const Map = [
        'Description' => [//update post
            'field' => 'set_description',
            'content' => '',
        ],
        'RecordModified' => [
            'field' => 'set_date_modified',
        ],
        'UnitPrice1' => [
            'field' => 'set_regular_price',
            'content' => 'input[name="_regular_price"]',
            'callback' => [ProductMapper::class, 'toFloat'],
        ],
        'UnitQuantity' => [
            'field' => 'set_stock_quantity',
            'content' => 'input[name="_stock"]',
        ],
    ];

}