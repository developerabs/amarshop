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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('title');
            $table->enum('type', [
                'custom',
                'page',
                'category',
                'brand'
            ]);
            // Related resource id
            $table->unsignedBigInteger('reference_id')->nullable();
            // Only for custom link
            $table->string('url')->nullable();
            // Parent menu support
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('menu_items')
                ->nullOnDelete();
            $table->integer('position')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
