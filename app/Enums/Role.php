<?php

namespace App\Enums;

enum Role: int
{
    case ADMIN = 1;

    case PRODUCTS_MANAGER = 2;

    case SALES_ASSISTANT = 3;
}
