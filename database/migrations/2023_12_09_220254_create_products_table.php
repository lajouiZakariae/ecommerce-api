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

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->text('description')->nullable();

            $table->unsignedFloat('cost')->nullable();
            $table->unsignedFloat('price')->nullable();

            $table->boolean('published')->default(false);

            $table->unsignedBigInteger('category_id')->nullable()->default(1);

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('SET DEFAULT')
                ->cascadeOnUpdate();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
