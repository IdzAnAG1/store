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
            $table->bigIncrements("product_id");
            $table->string("name", 250);
            $table->text("description")->nullable();
            $table->decimal("price", 12, 2);
            $table->unsignedBigInteger("category_id")->nullable();
            $table->unsignedBigInteger("stock_quantity")->default(0);
            $table->timestamps();

            $table->index("category_id", "idx_products_category");
            $table->foreign("category_id", "fk_products_category")
                ->references("category_id")->on("categories")
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });

        Schema::create("product_attributes", function (Blueprint $table) {
            $table->bigIncrements("attribute_id");
            $table->unsignedBigInteger("product_id");
            $table->string("attribute_name", 150);
            $table->string("value", 250);

            $table->index("product_id", "idx_product_attributes_product");

            $table->foreign("product_id", "fk_product_attributes_product")
                -> references("product_id")->on("products")
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unique(['product_id', 'attribute_name'], 'uniq_product_attr');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('products');
    }
};
