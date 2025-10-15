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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // fk brands
            $table->foreignId('brand_id')->constrained('brands')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // fk categories
            $table->foreignId('category_id')->constrained('categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('product_name')->unique();
            $table->string('product_desc')->nullable();
            $table->string('sku', 100)->unique();
            $table->string('barcode', 100);
            $table->decimal('price', 8, 2);
            $table->decimal('cost', 8, 2);
            $table->decimal('tax_rate', 5, 2);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
