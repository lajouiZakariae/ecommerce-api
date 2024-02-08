<?php

namespace App\Enums;

enum SortBy: string
{
    case CREATED_AT = 'created_at';

    case PRICE = 'price';

    case COST = 'cost';

    case STOCK_QUANTITY = 'stock_quantity';
}
