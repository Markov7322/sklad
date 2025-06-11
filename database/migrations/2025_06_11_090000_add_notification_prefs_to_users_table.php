<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_status_changes')->default(true);
            $table->boolean('notify_site')->default(true);
            $table->boolean('notify_balance_changes')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notify_status_changes', 'notify_site', 'notify_balance_changes']);
        });
    }
};
