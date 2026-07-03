<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'home_cost')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('home_cost', 10, 2)->default(0)->after('category');
            });
        }

        if (!Schema::hasColumn('products', 'shop_cost')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('shop_cost', 10, 2)->default(0)->after('home_cost');
            });
        }

        if (!Schema::hasColumn('products', 'home_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('home_price', 10, 2)->default(0)->after('shop_cost');
            });
        }

        if (!Schema::hasColumn('products', 'shop_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('shop_price', 10, 2)->default(0)->after('home_price');
            });
        }

        if (Schema::hasColumn('products', 'price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('price');
            });
        }

        if (Schema::hasColumn('products', 'wholesale_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('wholesale_price');
            });
        }

        if (Schema::hasColumn('products', 'cost')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('cost');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'home_cost')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn(['home_cost', 'shop_cost', 'home_price', 'shop_price']);
            });
        }
    }
};
