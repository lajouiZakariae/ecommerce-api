<?php

namespace App\Enums;

enum Status: string
{
	case PENDING = "pending";

	case IN_TRANSIT = "in transit";

	case DELIVERED = "delivered";

	case DELIVERY_ATTEMPT = "delivery attempt";

	case CANCELLED = "cancelled";

	case RETURN_TO_SENDER = "return to sender";
}
