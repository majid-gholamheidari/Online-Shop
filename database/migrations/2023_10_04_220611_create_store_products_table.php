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
        Schema::create('store_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('code');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('cover_img');
            $table->json('images');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('discount_price')->default(0)->comment('مبلغ تخفیف');
            $table->unsignedInteger('discount_percent')->default(0);
            $table->enum('special_offer', ['active', 'inactive'])->default('inactive');
            $table->date('special_offer_from')->nullable();
            $table->date('special_offer_to')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->integer('stock')->default(0);
            $table->json('tags')->nullable();
            $table->json('colors')->nullable();
            $table->json('attributes')->nullable();
            $table->json('properties')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_products');
    }
};
