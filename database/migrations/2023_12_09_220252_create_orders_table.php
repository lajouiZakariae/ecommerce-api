<?php

use App\Enums\OrderStatus;
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

            $table->timestamp('paid_at')->nullable();

            $table->unsignedBigInteger('client_id')->nullable();

            $table
                ->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->enum('status', OrderStatus::values());

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
