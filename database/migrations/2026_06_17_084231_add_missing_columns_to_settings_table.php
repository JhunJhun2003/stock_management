<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'website')) {
                $table->string('website')->nullable()->after('email');
            }
            if (!Schema::hasColumn('settings', 'tax_rate')) {
                $table->decimal('tax_rate', 5, 2)->default(0)->after('logo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['website', 'tax_rate']);
        });
    }
};