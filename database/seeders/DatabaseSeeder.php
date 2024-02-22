<?php

namespace Database\Seeders;

use App\Enums\Role as EnumsRole;
use App\Models\Category;
use App\Models\Client;
use App\Models\CouponCode;
use App\Models\Image;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Review;
use App\Models\Role;
use App\Models\Store;
use App\Models\Supplier;
use Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::insert([
            ["name" => "admin"],
            ["name" => "sales_assistant"],
        ]);

        \App\Models\User::factory()->create([
            'first_name' => 'Zakariae',
            'last_name' => 'Lajoui',
            'role_id' => EnumsRole::ADMIN,
            'email' => 'lajoui.zakariae.1@gmail.com',
            'password' => Hash::make('1234')
        ]);

        \App\Models\User::factory()->create([
            'first_name' => 'Ilham',
            'last_name' => 'El Maimouni',
            'role_id' => EnumsRole::SALES_ASSISTANT,
            'email' => 'ilhammaimouni269@gmail.com',
            'password' => Hash::make('1234')
        ]);

        Client::factory(5)->create();

        $payment_methods = [
            ["name" => "Credit Card"],
            ["name" => "Cash On Delivery"],
        ];

        PaymentMethod::insert($payment_methods);

        $randomStores = [
            [
                'name' => 'Casablanca',
            ],
            [
                'name' => 'Tangier',
            ],
            [
                'name' => 'Marrakech',
            ],
            [
                'name' => 'Agadir',
            ],
            [
                'name' => 'Fez',
            ],
        ];

        // Laravel insert statement
        Store::insert($randomStores);

        $electronicsCategories = [
            [
                'name' => 'Smartphones',
                'description' => 'Cutting-edge mobile devices with advanced features.'
            ],
            [
                'name' => 'Laptops',
                'description' => 'Portable computers for work, entertainment, and more.'
            ],
            [
                'name' => 'Audio Devices',
                'description' => 'Speakers, headphones, and other audio accessories.'
            ],
            [
                'name' => 'Gaming Gear',
                'description' => 'Consoles, controllers, and gaming peripherals.'
            ],
            [
                'name' => 'Smart Home',
                'description' => 'Devices for automating and enhancing home living.'
            ],
        ];

        Category::insert($electronicsCategories);

        $productsData = json_decode(File::get(base_path('json/products.json')));

        $productsData = collect($productsData)->map(function ($product) {
            $product->created_at = fake()->time();
            return $product;
        });

        foreach ($productsData as $product) {
            $product = Product::create((array) $product);

            if (in_array($product->id, [1, 2])) continue;

            Inventory::insert([
                'product_id' => $product->id,
                'quantity'  => fake()->numberBetween(0, 100),
                'min_stock_level' => 10,
                'max_stock_level' => 200,
            ]);

            Inventory::insert([
                'product_id' => $product->id,
                'quantity'  => fake()->numberBetween(0, 100),
                'min_stock_level' => 10,
                'max_stock_level' => 200,
            ]);
        }

        Review::factory(50)->create();

        $coupon_codes = [
            ["code" => "LOREM03", "amount" => 25],
            ["code" => "LOREM05", "amount" => 10],
            ["code" => "LOREM07", "amount" => 10],
            ["code" => "LOREM09", "amount" => 5],
            ["code" => "LOREM12", "amount" => 10],
            ["code" => "LOREM14", "amount" => 20],
            ["code" => "LOREM16", "amount" => 40],
            ["code" => "LOREM19", "amount" => 50],
        ];

        CouponCode::insert($coupon_codes);

        $suppliers = [
            [
                "name" => "Tech Innovators",
                "email" => "techinnovators@example.com",
                "phone_number" => "+212612345678",
                "address" => "123 Tech Street, Cityville, Morocco",
            ],
            [
                "name" => "ElectroTech Solutions",
                "email" => "electrotech@example.com",
                "phone_number" => "+212623456789",
                "address" => "456 Circuit Avenue, Innovation City, Morocco",
            ],
            [
                "name" => "Gadget Galaxy",
                "email" => "gadgetgalaxy@example.com",
                "phone_number" => "+212634567890",
                "address" => "789 Device Lane, Tech Town, Morocco",
            ],
            [
                "name" => "Digital Dynamics",
                "email" => "digitaldynamics@example.com",
                "phone_number" => "+212645678901",
                "address" => "1010 Byte Boulevard, Future City, Morocco",
            ]
        ];

        Supplier::insert($suppliers);

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
                "status" => fake()->randomElement(["pending", "in transit", "delivered", "delivery attempt", "cancelled", "return to sender"]),
                "coupon_code_id" => 1,
                "payment_method_id" => fake()->numberBetween(1, 2),
            ],
            [
                "client_id" => 1,
                "status" => fake()->randomElement(["pending", "in transit", "delivered", "delivery attempt", "cancelled", "return to sender"]),
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

        // OrderItem::insert($order_items);

        collect($order_items)->each(function (array $orderItemAsArray) {
            $orderItem = new OrderItem($orderItemAsArray);
            $orderItem->save();
        });

        for ($i = 1; $i <= 30; $i++) {
            Image::insert([
                'alt_text' => fake()->word,
                'path' => 'products/placeholder.jpg',
                'product_id' => $i,
            ]);
        }

        // foreach (File::allFiles(storage_path('app/public/products')) as $value) {
        //     Image::insert([
        //         'alt_text' => fake()->word,
        //         'path' => 'products/' . $value->getFilename(),
        //         'product_id' => fake()->numberBetween(1, 20),
        //     ]);
        // }
    }
}
