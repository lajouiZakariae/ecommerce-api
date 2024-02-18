<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'status',
        'city',
        'payment_method_id',
        'zip_code',
        'coupon_code_id',
        'address',
        'delivery',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'delivery' => 'boolean',
    ];

    protected $hidden = ['updated_at'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function couponCode(): BelongsTo
    {
        return $this->belongsTo(CouponCode::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function setTotalQuantity()
    {
        $total_quantity  =  $this->orderItems->reduce(
            fn ($acc, OrderItem $orderItem) => $acc + $orderItem->quantity,
            0
        );

        $this->total_quantity = $total_quantity;
    }

    public function setTotalUnitPrice()
    {
        $total_unit_price  =  $this->orderItems->reduce(
            fn ($acc, OrderItem $orderItem) => $acc + $orderItem->product->price,
            0
        );

        $this->total_unit_price = round($total_unit_price, 2);
    }

    public function setTotalPrice(): void
    {
        $total_price = $this->orderItems->reduce(function ($acc, OrderItem $orderItem) {
            return $acc + ($orderItem->quantity * $orderItem->product->price);
        }, 0);

        $this->total_price = round($total_price, 2);
    }
}
