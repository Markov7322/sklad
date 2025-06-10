<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skladchinas', function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
        });

        $records = DB::table('skladchinas')->select('id', 'name')->get();
        foreach ($records as $record) {
            DB::table('skladchinas')
                ->where('id', $record->id)
                ->update(['slug' => Str::slug($record->name)]);
        }
    }

    public function down(): void
    {
        Schema::table('skladchinas', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
