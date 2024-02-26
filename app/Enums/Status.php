<?php

namespace App\Enums;

enum Status: string
{
	case REVIEW = "review";

	case PENDING = "pending";

	case SHIPPING = "shipping";

	case CANCELLED = "cancelled";

	case DELIVERY_ATTEMPT = "delivery attempt";

	case DELIVERED = "delivered";

	case RETURN_TO_SENDER = "return to sender";
}
