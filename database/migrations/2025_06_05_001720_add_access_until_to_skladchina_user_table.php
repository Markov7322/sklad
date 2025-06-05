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
        Schema::table('skladchina_user', function (Blueprint $table) {
            $table->timestamp('access_until')->nullable()->after('paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skladchina_user', function (Blueprint $table) {
            $table->dropColumn('access_until');
        });
    }
};
