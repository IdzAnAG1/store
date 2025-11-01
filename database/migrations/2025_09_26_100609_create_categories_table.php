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
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('category_id');
            $table->string('category_name', 150);
            $table->text('description')->nullable();

            $table->unsignedBigInteger('parent_category_id')->nullable();

            $table->index('parent_category_id', 'idx_categories_parent');

            $table->foreign('parent_category_id', 'fk_categories_parent')
                ->references('category_id')->on('categories')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
