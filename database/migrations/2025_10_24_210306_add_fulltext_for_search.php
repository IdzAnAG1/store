<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ВАЖНО: таблицы должны быть InnoDB и utf8mb4 (обычно так по умолчанию)
        Schema::table('products', function (Blueprint $table) {
            $table->fullText(['name', 'description'], 'ft_products');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->fullText('category_name', 'ft_categories_name');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropFullText('ft_products');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropFullText('ft_categories_name');
        });
    }
};
