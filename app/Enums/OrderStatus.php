<?php

namespace App\Enums;

enum OrderStatus: string
{
	case REVIEW = "review";

	case PENDING = "pending";

	case SHIPPING = "shipping";

	case CANCELLED = "cancelled";

	case DELIVERY_ATTEMPT = "delivery attempt";

	case DELIVERED = "delivered";

	case RETURN_TO_SENDER = "return to sender";

	public static function values(): array
	{
		return array_column(self::cases(), 'value');
	}
}
