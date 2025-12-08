<?php
// database/migrations/xxxx_xx_xx_create_product_images_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->integer('display_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index('product_id');
            $table->index('display_order');
        });

        // Migrate data dari products.image ke product_images
        DB::statement("
            INSERT INTO product_images (product_id, image_path, display_order, is_primary, created_at, updated_at)
            SELECT id, image, 0, 1, created_at, updated_at
            FROM products
            WHERE image IS NOT NULL
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};