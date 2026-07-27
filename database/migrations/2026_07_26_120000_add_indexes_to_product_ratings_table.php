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
        Schema::table('product_ratings', function (Blueprint $table) {
            $table->index(['product_id', 'is_approved'], 'product_ratings_product_approved_idx');
            $table->index(['user_id', 'product_id'], 'product_ratings_user_product_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_ratings', function (Blueprint $table) {
            $table->dropIndex('product_ratings_product_approved_idx');
            $table->dropIndex('product_ratings_user_product_idx');
        });
    }
};
