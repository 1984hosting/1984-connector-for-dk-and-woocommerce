<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\ProductMapper;

class ProductMap extends ProductMapper
{
    const Map = [
        'ItemCode' => [
            'field' => 'sku',
        ],
        'Description' => [//update post
            'field' => 'description',
        ],
        'RecordModified' => [
            'field' => 'date_modified',
        ],
        'UnitPrice1' => [
            'field' => 'regular_price',
            'callback' => [ProductMapper::class, 'toFloat'],
        ],
        'UnitQuantity' => [
            'field' => 'stock_quantity',
            //'callback' => [ProductMapper::class, 'activateStock'],
        ],
        'set_manage_stock' => [
            'field' => 'manage_stock',
            'callback' => [ProductMapper::class, 'setBoolTrue'],
        ],
    ];

}