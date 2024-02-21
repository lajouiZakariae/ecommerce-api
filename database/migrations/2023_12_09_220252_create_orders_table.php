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
            $table->id();

            $table->unsignedBigInteger('total_quantity')->nullable()->default(0);

            $table->unsignedFloat('total_price_per_unit')->nullable()->default(0);

            $table->unsignedFloat('total_price')->nullable()->default(0);

            $table->timestamp('paid_at')->nullable();

            $table->unsignedBigInteger('client_id')->nullable();

            $table
                ->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->enum('status', ["pending", "in transit", "delivered", "delivery attempt", "cancelled", "return to sender"]);

            $table->unsignedBigInteger('payment_method_id')->nullable();

            $table
                ->foreign('payment_method_id')
                ->references('id')
                ->on('payment_methods')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->unsignedBigInteger('coupon_code_id')->nullable();
            $table
                ->foreign('coupon_code_id')
                ->references('id')
                ->on('coupon_codes')
                ->cascadeOnUpdate()
                ->nullOnDelete();



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
