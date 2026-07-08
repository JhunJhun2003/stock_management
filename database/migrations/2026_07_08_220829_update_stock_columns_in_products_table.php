<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('home_stock')->default(0)->after('shop_price');
            $table->integer('shop_stock')->default(0)->after('home_stock');

            $table->dropColumn('stock');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock')->default(0)->after('shop_price');

            $table->dropColumn(['home_stock', 'shop_stock']);
        });
    }
};