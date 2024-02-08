<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone_number');
            $table->enum('status', [
                "pending", "in transit", "delivered", "delivery attempt", "cancelled", "return to sender"
            ]);
            $table->string('city');

            $table->unsignedInteger('payment_method_id')->nullable();

            $table
                ->foreign('payment_method_id')
                ->references('id')
                ->on('payment_methods')
                ->cascadeOnUpdate()
                ->nullOnDelete();


            $table->unsignedInteger('coupon_code_id')->nullable();
            $table
                ->foreign('coupon_code_id')
                ->references('id')
                ->on('coupon_codes')
                ->cascadeOnUpdate()
                ->nullOnDelete();


            $table->string('zip_code');
            $table->string('address');
            $table->boolean('delivery');

            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
