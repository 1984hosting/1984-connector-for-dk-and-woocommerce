<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\ProductMapper;

class ProductMap extends ProductMapper
{
    const Map = [
        'ItemCode' => [
            'field' => 'sku',
        ],
        'Description' => [
            'field' => 'name',
        ],
        'Description2' => [
            'field' => 'description',
        ],
        'RecordModified' => [
            'field' => 'date_modified',
        ],
        // Import price with VAT. #29
        'UnitPrice1WithTax' => [
            'field' => 'regular_price',
            'callback' => [ProductMapper::class, 'toFloat'],
        ],
        'UnitQuantity' => [
            'field' => 'stock_quantity',
        ],
        'set_manage_stock' => [
            'field' => 'manage_stock',
            'callback' => [ProductMapper::class, 'setBoolTrue'],
        ],
    ];
}
