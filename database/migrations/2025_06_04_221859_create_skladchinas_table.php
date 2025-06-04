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
        Schema::create('skladchinas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('cover')->nullable();
            $table->decimal('full_price', 10, 2);
            $table->decimal('member_price', 10, 2);
            $table->string('status')->default('draft');
            $table->text('attachment')->nullable();
            $table->foreignId('organizer_id')->constrained('users');
            $table->foreignId('category_id')->constrained('categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skladchinas');
    }
};
