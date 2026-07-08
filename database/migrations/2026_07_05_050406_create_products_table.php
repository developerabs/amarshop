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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('code', 50)->unique()->index();
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('brand_id')->nullable()->index();
            $table->string('name', 100)->index();
            $table->string('slug')->unique();
            $table->string('model', 100)->nullable();
            $table->decimal('cost', 8, 2);
            $table->decimal('price', 8, 2);
            $table->decimal('sale_price', 8, 2)->nullable();
            $table->decimal('wholesale_price', 8, 2);
            $table->integer('alert_quantity')->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->string('tax_type', 50)->nullable();
            $table->integer('total_stock')->default(0);

            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->enum('discount_type', ['fixed', 'percentage'])->nullable();
            $table->timestamp('discount_start_at')->nullable();
            $table->timestamp('discount_end_at')->nullable();

            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('image')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            $table->boolean('is_flash_deal')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_daily_offer')->default(false);
            $table->boolean('is_best_deal')->default(false);
            $table->boolean('is_top_sale')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->boolean('is_wholesale')->default(false);
            $table->boolean('has_variants')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
