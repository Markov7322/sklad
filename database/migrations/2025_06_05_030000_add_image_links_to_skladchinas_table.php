<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skladchinas', function (Blueprint $table) {
            $table->json('image_links')->nullable()->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('skladchinas', function (Blueprint $table) {
            $table->dropColumn('image_links');
        });
    }
};
