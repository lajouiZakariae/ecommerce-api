<?php

namespace Database\Seeders;

use App\Enums\Role as EnumsRole;
use App\Enums\Status;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Role;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ClientSeeder::class,
            StoreSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class,
            ImageSeeder::class,
            PaymentMethodSeeder::class,
            CouponCodeSeeder::class,
            SupplierSeeder::class,
        ]);

        $purchases = [
            ["supplier_id" => 1, "paid" => true, "delivery_date" => "2023-11-25 14:30:00", "payment_method_id" => fake()->numberBetween(1, 2), "store_id" => fake()->numberBetween(1, 5)],
            ["supplier_id" => 2, "paid" => false, "delivery_date" => "2023-11-26 10:45:00", "payment_method_id" => fake()->numberBetween(1, 2), "store_id" => fake()->numberBetween(1, 5)],
            ["supplier_id" => 1, "paid" => true, "delivery_date" => "2023-11-27 16:15:00", "payment_method_id" => fake()->numberBetween(1, 2), "store_id" => fake()->numberBetween(1, 5)],
            ["supplier_id" => 1, "paid" => false, "delivery_date" => "2023-11-28 12:00:00", "payment_method_id" => fake()->numberBetween(1, 2), "store_id" => fake()->numberBetween(1, 5)]
        ];

        Purchase::insert($purchases);

        $purchase_items = [
            ["purchase_id" => 1, "product_id" => 1, "quantity" => 5],
            ["purchase_id" => 2, "product_id" => 2, "quantity" => 3],
            ["purchase_id" => 3, "product_id" => 3, "quantity" => 8],
            ["purchase_id" => 2, "product_id" => 4, "quantity" => 2],
            ["purchase_id" => 3, "product_id" => 5, "quantity" => 5],
            ["purchase_id" => 2, "product_id" => 6, "quantity" => 3],
            ["purchase_id" => 1, "product_id" => 7, "quantity" => 8],
            ["purchase_id" => 4, "product_id" => 8, "quantity" => 2]
        ];

        PurchaseItem::insert($purchase_items);

        $orders = [
            [
                "client_id" => 1,
                "status" => fake()->randomElement(["pending", "shipping", "delivered", "delivery attempt", "cancelled", "return to sender"]),
                "coupon_code_id" => 1,
                "payment_method_id" => fake()->numberBetween(1, 2),
            ],
            [
                "client_id" => 1,
                "status" => fake()->randomElement(["pending", "shipping", "delivered", "delivery attempt", "cancelled", "return to sender"]),
                "coupon_code_id" => 2,
                "payment_method_id" => fake()->numberBetween(1, 2),
            ],
            [
                "client_id" => 1,
                "status" => Status::PENDING,
                "coupon_code_id" => 2,
                "payment_method_id" => fake()->numberBetween(1, 2),
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }

        $order_items = [
            [
                "order_id" => 1,
                "product_id" => 1,
                "quantity" => 5,
                "product_price" => Product::find(1)->price
            ],
            [
                "order_id" => 1,
                "product_id" => 2,
                "quantity" => 3,
                "product_price" => Product::find(2)->price
            ],
            [
                "order_id" => 1,
                "product_id" => 3,
                "quantity" => 8,
                "product_price" => Product::find(3)->price
            ],
            [
                "order_id" => 2,
                "product_id" => 4,
                "quantity" => 2,
                "product_price" => Product::find(4)->price
            ],
            [
                "order_id" => 2,
                "product_id" => 5,
                "quantity" => 2,
                "product_price" => Product::find(5)->price
            ],

        ];

        collect($order_items)->each(function (array $orderItemAsArray) {
            $orderItem = new OrderItem($orderItemAsArray);
            $orderItem->save();
        });
    }
}
