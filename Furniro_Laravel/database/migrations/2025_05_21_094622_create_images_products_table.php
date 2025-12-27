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
        Schema::create('images_products', function (Blueprint $table) {
            $table->id();
            // Liên kết với cột id trong bảng products
            $table->unsignedBigInteger('product_id');
            $table->string('image');
            //liên kết với id trong product
            $table->foreign('product_id')
            ->references('id')->on('products')
            ->onDelete('cascade');// Khi xoá product thì xoá luôn ảnh phụ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images_products');
    }
};
