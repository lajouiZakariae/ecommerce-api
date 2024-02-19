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
use File;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::insert([
            ["name" => "admin"],
            ["name" => "creator"]
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
            'role_id' => EnumsRole::ADMIN,
            'email' => 'ilhammaimouni269@gmail.com',
            'password' => Hash::make('1234')
        ]);

        Client::factory(3)->create();

        // \App\Models\User::factory()->create([
        //     'first_name' => 'Zakariae',
        //     'last_name' => 'Lajoui',
        //     'role_id' => EnumsRole::ADMIN,
        //     'email' => 'lajoui.zakariae.1@gmail.com',
        //     'password' => Hash::make('1234')
        // ]);

        $payment_methods = [
            ["name" => "cheque"],
            ["name" => "cash"],
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

        $productsData = [
            [
                'title' => 'Smartphone',
                'description' => 'A high-end smartphone with advanced features.',
                'price' => 699.99,
                'cost' => 499.99,
                'category_id' => 1,
                'published' => true,
                'store_id' => 1,
            ],
            [
                'title' => 'Laptop',
                'description' => 'A powerful laptop for gaming and productivity.',
                'price' => 1299.99,
                'cost' => 899.99,
                'category_id' => 2,
                'published' => false,
                'store_id' => 2,
            ],
            [
                'title' => 'Wireless Earbuds',
                'description' => 'High-quality wireless earbuds with noise cancellation.',
                'price' => 129.99,
                'cost' => 79.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Smart TV',
                'description' => 'A large-screen smart TV with 4K resolution.',
                'price' => 899.99,
                'cost' => 699.99,
                'category_id' => 4,
                'published' => true,
                'store_id' => 4,
            ],
            [
                'title' => 'Gaming Console',
                'description' => 'The latest gaming console with high-performance hardware.',
                'price' => 499.99,
                'cost' => 349.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Laptop',
                'description' => 'A sleek and lightweight laptop for everyday use.',
                'price' => 899.99,
                'cost' => 599.99,
                'category_id' => 2,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Bluetooth Speaker',
                'description' => 'Portable Bluetooth speaker with enhanced bass.',
                'price' => 79.99,
                'cost' => 49.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 3,
            ],
            [
                'title' => 'Tablet',
                'description' => 'Versatile tablet with a high-resolution display.',
                'price' => 449.99,
                'cost' => 299.99,
                'category_id' => 1,
                'published' => true,
                'store_id' => 1,
            ],
            [
                'title' => 'Gaming Mouse',
                'description' => 'Precision gaming mouse with customizable RGB lighting.',
                'price' => 49.99,
                'cost' => 29.99,
                'category_id' => 5,
                'published' => true,
                'store_id' => 5,
            ],
            [
                'title' => 'Fitness Tracker',
                'description' => 'Advanced fitness tracker with heart rate monitoring.',
                'price' => 129.99,
                'cost' => 79.99,
                'category_id' => 2,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => '4K Monitor',
                'description' => 'Ultra-high-definition 4K monitor for crisp visuals.',
                'price' => 499.99,
                'cost' => 349.99,
                'category_id' => 4,
                'published' => true,
                'store_id' => 4,
            ],
            [
                'title' => 'Wireless Keyboard and Mouse Combo',
                'description' => 'Ergonomic wireless keyboard and mouse set for productivity.',
                'price' => 79.99,
                'cost' => 49.99,
                'category_id' => 2,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Digital Camera',
                'description' => 'High-resolution digital camera for capturing moments.',
                'price' => 699.99,
                'cost' => 499.99,
                'category_id' => 5,
                'published' => true,
                'store_id' => 3,
            ],
            [
                'title' => 'External Hard Drive',
                'description' => 'Large-capacity external hard drive for data storage.',
                'price' => 129.99,
                'cost' => 89.99,
                'category_id' => 4,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Smart Home Security Camera',
                'description' => 'Wireless smart home security camera for surveillance.',
                'price' => 149.99,
                'cost' => 99.99,
                'category_id' => 1,
                'published' => true,
                'store_id' => 3
            ],
            [
                'title' => 'Smart Thermostat',
                'description' => 'Wi-Fi enabled smart thermostat for home temperature control.',
                'price' => 129.99,
                'cost' => 89.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Electric Toothbrush',
                'description' => 'Advanced electric toothbrush with multiple cleaning modes.',
                'price' => 59.99,
                'cost' => 39.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 3,
            ],
            [
                'title' => 'Soundbar',
                'description' => 'High-quality soundbar for enhanced audio experience.',
                'price' => 149.99,
                'cost' => 99.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 3,
            ],
            [
                'title' => 'Robot Vacuum Cleaner',
                'description' => 'Smart robot vacuum cleaner for automated cleaning.',
                'price' => 299.99,
                'cost' => 199.99,
                'category_id' => 4,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Portable Power Bank',
                'description' => 'Compact portable power bank for charging devices on the go.',
                'price' => 29.99,
                'cost' => 19.99,
                'category_id' => 1,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Smartphone',
                'description' => 'A high-end smartphone with advanced features.',
                'price' => 699.99,
                'cost' => 499.99,
                'category_id' => 1,
                'published' => true,
                'store_id' => 1,
            ],
            [
                'title' => 'Laptop',
                'description' => 'A powerful laptop for gaming and productivity.',
                'price' => 1299.99,
                'cost' => 899.99,
                'category_id' => 2,
                'published' => false,
                'store_id' => 2,
            ],
            [
                'title' => 'Wireless Earbuds',
                'description' => 'High-quality wireless earbuds with noise cancellation.',
                'price' => 129.99,
                'cost' => 79.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Smart TV',
                'description' => 'A large-screen smart TV with 4K resolution.',
                'price' => 899.99,
                'cost' => 699.99,
                'category_id' => 4,
                'published' => true,
                'store_id' => 4,
            ],
            [
                'title' => 'Gaming Console',
                'description' => 'The latest gaming console with high-performance hardware.',
                'price' => 499.99,
                'cost' => 349.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Laptop',
                'description' => 'A sleek and lightweight laptop for everyday use.',
                'price' => 899.99,
                'cost' => 599.99,
                'category_id' => 2,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Bluetooth Speaker',
                'description' => 'Portable Bluetooth speaker with enhanced bass.',
                'price' => 79.99,
                'cost' => 49.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 3,
            ],
            [
                'title' => 'Tablet',
                'description' => 'Versatile tablet with a high-resolution display.',
                'price' => 449.99,
                'cost' => 299.99,
                'category_id' => 1,
                'published' => true,
                'store_id' => 1,
            ],
            [
                'title' => 'Gaming Mouse',
                'description' => 'Precision gaming mouse with customizable RGB lighting.',
                'price' => 49.99,
                'cost' => 29.99,
                'category_id' => 5,
                'published' => true,
                'store_id' => 5,
            ],
            [
                'title' => 'Fitness Tracker',
                'description' => 'Advanced fitness tracker with heart rate monitoring.',
                'price' => 129.99,
                'cost' => 79.99,
                'category_id' => 2,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => '4K Monitor',
                'description' => 'Ultra-high-definition 4K monitor for crisp visuals.',
                'price' => 499.99,
                'cost' => 349.99,
                'category_id' => 4,
                'published' => true,
                'store_id' => 4,
            ],
            [
                'title' => 'Wireless Keyboard and Mouse Combo',
                'description' => 'Ergonomic wireless keyboard and mouse set for productivity.',
                'price' => 79.99,
                'cost' => 49.99,
                'category_id' => 2,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Digital Camera',
                'description' => 'High-resolution digital camera for capturing moments.',
                'price' => 699.99,
                'cost' => 499.99,
                'category_id' => 5,
                'published' => true,
                'store_id' => 3,
            ],
            [
                'title' => 'External Hard Drive',
                'description' => 'Large-capacity external hard drive for data storage.',
                'price' => 129.99,
                'cost' => 89.99,
                'category_id' => 4,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Smart Home Security Camera',
                'description' => 'Wireless smart home security camera for surveillance.',
                'price' => 149.99,
                'cost' => 99.99,
                'category_id' => 1,
                'published' => true,
                'store_id' => 3
            ],
            [
                'title' => 'Smart Thermostat',
                'description' => 'Wi-Fi enabled smart thermostat for home temperature control.',
                'price' => 129.99,
                'cost' => 89.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Electric Toothbrush',
                'description' => 'Advanced electric toothbrush with multiple cleaning modes.',
                'price' => 59.99,
                'cost' => 39.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 3,
            ],
            [
                'title' => 'Soundbar',
                'description' => 'High-quality soundbar for enhanced audio experience.',
                'price' => 149.99,
                'cost' => 99.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 3,
            ],
            [
                'title' => 'Robot Vacuum Cleaner',
                'description' => 'Smart robot vacuum cleaner for automated cleaning.',
                'price' => 299.99,
                'cost' => 199.99,
                'category_id' => 4,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Portable Power Bank',
                'description' => 'Compact portable power bank for charging devices on the go.',
                'price' => 29.99,
                'cost' => 19.99,
                'category_id' => 1,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Smartphone',
                'description' => 'A high-end smartphone with advanced features.',
                'price' => 699.99,
                'cost' => 499.99,
                'category_id' => 1,
                'published' => true,
                'store_id' => 1,
            ],
            [
                'title' => 'Laptop',
                'description' => 'A powerful laptop for gaming and productivity.',
                'price' => 1299.99,
                'cost' => 899.99,
                'category_id' => 2,
                'published' => false,
                'store_id' => 2,
            ],
            [
                'title' => 'Wireless Earbuds',
                'description' => 'High-quality wireless earbuds with noise cancellation.',
                'price' => 129.99,
                'cost' => 79.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Smart TV',
                'description' => 'A large-screen smart TV with 4K resolution.',
                'price' => 899.99,
                'cost' => 699.99,
                'category_id' => 4,
                'published' => true,
                'store_id' => 4,
            ],
            [
                'title' => 'Gaming Console',
                'description' => 'The latest gaming console with high-performance hardware.',
                'price' => 499.99,
                'cost' => 349.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Laptop',
                'description' => 'A sleek and lightweight laptop for everyday use.',
                'price' => 899.99,
                'cost' => 599.99,
                'category_id' => 2,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Bluetooth Speaker',
                'description' => 'Portable Bluetooth speaker with enhanced bass.',
                'price' => 79.99,
                'cost' => 49.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 3,
            ],
            [
                'title' => 'Tablet',
                'description' => 'Versatile tablet with a high-resolution display.',
                'price' => 449.99,
                'cost' => 299.99,
                'category_id' => 1,
                'published' => true,
                'store_id' => 1,
            ],
            [
                'title' => 'Gaming Mouse',
                'description' => 'Precision gaming mouse with customizable RGB lighting.',
                'price' => 49.99,
                'cost' => 29.99,
                'category_id' => 5,
                'published' => true,
                'store_id' => 5,
            ],
            [
                'title' => 'Fitness Tracker',
                'description' => 'Advanced fitness tracker with heart rate monitoring.',
                'price' => 129.99,
                'cost' => 79.99,
                'category_id' => 2,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => '4K Monitor',
                'description' => 'Ultra-high-definition 4K monitor for crisp visuals.',
                'price' => 499.99,
                'cost' => 349.99,
                'category_id' => 4,
                'published' => true,
                'store_id' => 4,
            ],
            [
                'title' => 'Wireless Keyboard and Mouse Combo',
                'description' => 'Ergonomic wireless keyboard and mouse set for productivity.',
                'price' => 79.99,
                'cost' => 49.99,
                'category_id' => 2,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Digital Camera',
                'description' => 'High-resolution digital camera for capturing moments.',
                'price' => 699.99,
                'cost' => 499.99,
                'category_id' => 5,
                'published' => true,
                'store_id' => 3,
            ],
            [
                'title' => 'External Hard Drive',
                'description' => 'Large-capacity external hard drive for data storage.',
                'price' => 129.99,
                'cost' => 89.99,
                'category_id' => 4,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Smart Home Security Camera',
                'description' => 'Wireless smart home security camera for surveillance.',
                'price' => 149.99,
                'cost' => 99.99,
                'category_id' => 1,
                'published' => true,
                'store_id' => 3
            ],
            [
                'title' => 'Smart Thermostat',
                'description' => 'Wi-Fi enabled smart thermostat for home temperature control.',
                'price' => 129.99,
                'cost' => 89.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Electric Toothbrush',
                'description' => 'Advanced electric toothbrush with multiple cleaning modes.',
                'price' => 59.99,
                'cost' => 39.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 3,
            ],
            [
                'title' => 'Soundbar',
                'description' => 'High-quality soundbar for enhanced audio experience.',
                'price' => 149.99,
                'cost' => 99.99,
                'category_id' => 3,
                'published' => true,
                'store_id' => 3,
            ],
            [
                'title' => 'Robot Vacuum Cleaner',
                'description' => 'Smart robot vacuum cleaner for automated cleaning.',
                'price' => 299.99,
                'cost' => 199.99,
                'category_id' => 4,
                'published' => true,
                'store_id' => 2,
            ],
            [
                'title' => 'Portable Power Bank',
                'description' => 'Compact portable power bank for charging devices on the go.',
                'price' => 29.99,
                'cost' => 19.99,
                'category_id' => 1,
                'published' => true,
                'store_id' => 2,
            ]

        ];

        $productsData = collect($productsData)->map(function ($product) {
            $product['created_at'] = fake()->date();
            return $product;
        });

        foreach ($productsData as $product) {
            $product = Product::create($product);

            Inventory::insert([
                'product_id' => $product->id,
                'store_id'  => 1,
                'quantity'  => fake()->numberBetween(0, 100),
                'min_stock_level' => 10,
                'max_stock_level' => 200
            ]);

            Inventory::insert([
                'product_id' => $product->id,
                'store_id'  => 2,
                'quantity'  => fake()->numberBetween(0, 100),
                'min_stock_level' => 10,
                'max_stock_level' => 200
            ]);
        }

        // Inventory::factory(25)->create();

        $reviews = [
            [
                'email' => fake()->email,
                "body" => "Excellent product! Highly recommended.",
                "product_id" => 1,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Not satisfied with the quality.",
                "product_id" => 2,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Amazing features, worth the price.",
                "product_id" => 3,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Average product, needs improvement.",
                "product_id" => 4,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Great customer service, prompt delivery.",
                "product_id" => 5,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Terrible experience with this product.",
                "product_id" => 6,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Impressed with the build quality.",
                "product_id" => 7,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Good value for money.",
                "product_id" => 8,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Fast shipping, satisfied with the purchase.",
                "product_id" => 9,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Poor customer support.",
                "product_id" => 1,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Impressive performance, exceeded my expectations.",
                "product_id" => 11,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Decent product for the price.",
                "product_id" => 12,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Hassle-free returns and refunds.",
                "product_id" => 13,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Faulty product, disappointed with the purchase.",
                "product_id" => 14,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Responsive customer service, resolved my issues quickly.",
                "product_id" => 15,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Excellent build quality, sturdy and durable.",
                "product_id" => 16,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Difficult to assemble, unclear instructions.",
                "product_id" => 17,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Sleek design, fits perfectly in my space.",
                "product_id" => 18,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Unresponsive customer support.",
                "product_id" => 19,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Happy with the purchase, great value.",
                "product_id" => 20,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Smooth transaction, quick delivery.",
                "product_id" => 1,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Disappointed with the product quality.",
                "product_id" => 2,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Impressed with the customer service.",
                "product_id" => 20,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Average product, nothing exceptional.",
                "product_id" => 1,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Great packaging, no damage during shipping.",
                "product_id" => 2,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Defective product, had to return it.",
                "product_id" => 3,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Excellent warranty coverage.",
                "product_id" => 4,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "User-friendly interface, easy setup.",
                "product_id" => 5,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Poorly designed, not user-friendly.",
                "product_id" => 6,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Affordable and reliable product.",
                "product_id" => 7,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Exceptional build quality, highly recommended.",
                "product_id" => 8,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Average product, not worth the price.",
                "product_id" => 9,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Fast and secure payment process.",
                "product_id" => 10,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Durable and long-lasting, great investment.",
                "product_id" => 11,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Unreliable product, constant issues.",
                "product_id" => 12,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Fantastic customer service experience.",
                "product_id" => 17,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Compact design, fits well in small spaces.",
                "product_id" => 14,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Difficult to assemble, requires professional help.",
                "product_id" => 15,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Happy with the purchase, exceeded expectations.",
                "product_id" => 16,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Unresponsive customer support, frustrating experience.",
                "product_id" => 14,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Affordable and reliable, great value for money.",
                "product_id" => 11,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Disappointed with the shipping delays.",
                "product_id" => 13,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Sleek design, enhances the overall aesthetics.",
                "product_id" => 18,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Faulty product, major quality issues.",
                "product_id" => 15,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Responsive and helpful customer service.",
                "product_id" => 9,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Innovative features, sets it apart from others.",
                "product_id" => 6,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Product arrived damaged, poor packaging.",
                "product_id" => 7,
                "approved" => false
            ],
            [
                'email' => fake()->email,
                "body" => "Decent product, met my basic requirements.",
                "product_id" => 19,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Easy to use, suitable for beginners.",
                "product_id" => 4,
                "approved" => true
            ],
            [
                'email' => fake()->email,
                "body" => "Overpriced for the quality provided.",
                "product_id" => 6,
                "approved" => false
            ]
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }

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
            ["order_id" => 1, "product_id" => 1, "quantity" => 5],
            ["order_id" => 1, "product_id" => 2, "quantity" => 3],
            ["order_id" => 2, "product_id" => 2, "quantity" => 3],
            ["order_id" => 1, "product_id" => 3, "quantity" => 8],
            ["order_id" => 2, "product_id" => 4, "quantity" => 2],
            ["order_id" => 2, "product_id" => 5, "quantity" => 2],

        ];

        OrderItem::insert($order_items);


        File::put(base_path('storage/app/public/products/hello.txt'), 'hello world');

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
