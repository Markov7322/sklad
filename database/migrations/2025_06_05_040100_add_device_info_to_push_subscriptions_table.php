<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(config('webpush.table_name'), function (Blueprint $table) {
            $table->string('device_info')->nullable()->after('content_encoding');
        });
    }

    public function down(): void
    {
        Schema::table(config('webpush.table_name'), function (Blueprint $table) {
            $table->dropColumn('device_info');
        });
    }
};
