<?php
// database/migrations/2024_01_05_000000_create_reviews_table.php
// REPLACE file migration reviews yang lama dengan ini

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // NULLABLE untuk guest
            
            // Rating & Comment
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            
            // Guest reviewer data (jika user_id = null)
            $table->string('guest_name')->nullable();
            $table->string('guest_phone')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_location')->nullable(); // Kota/Kabupaten
            
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('product_id');
            $table->index('user_id');
            $table->index('guest_location');
            
            // Tidak ada unique constraint karena:
            // - User bisa review 1x per produk (dihandle di controller)
            // - Guest diidentifikasi dari email (dihandle di controller)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};