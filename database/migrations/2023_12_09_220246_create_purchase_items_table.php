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

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('purchase_id');

            $table
                ->foreign('purchase_id')
                ->references('id')
                ->on('purchases')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedInteger('product_id');

            $table
                ->foreign('product_id')
                ->references('id')
                ->on('products')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedInteger('quantity');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
