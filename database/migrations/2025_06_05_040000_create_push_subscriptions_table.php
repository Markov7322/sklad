<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::connection(config('webpush.database_connection'))
            ->create(config('webpush.table_name'), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('subscribable');
                $table->string('endpoint', 500)->unique();
                $table->string('public_key')->nullable();
                $table->string('auth_token')->nullable();
                $table->string('content_encoding')->nullable();
                $table->timestamps();
            });
    }

    public function down()
    {
        Schema::connection(config('webpush.database_connection'))
            ->dropIfExists(config('webpush.table_name'));
    }
};
