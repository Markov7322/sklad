<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skladchina_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skladchina_id')->constrained()->onDelete('cascade');
            $table->string('path');
            $table->unsignedTinyInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skladchina_images');
    }
};
