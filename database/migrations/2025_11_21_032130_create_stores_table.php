<?php
// database/migrations/2024_01_02_000000_create_stores_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('pic_name')->nullable();
            $table->string('pic_phone')->nullable();
            $table->string('pic_email')->nullable();
            $table->string('pic_street')->nullable(); // Nama jalan
            $table->string('pic_rt')->nullable();
            $table->string('pic_rw')->nullable();
            $table->string('pic_kelurahan')->nullable();
            $table->string('pic_district_id')->nullable();
            $table->string('pic_district_name')->nullable(); // Kecamatan
            $table->string('pic_city_name')->nullable(); // Kabupaten/Kota
            $table->string('pic_city_id')->nullable();
            $table->string('pic_province_id')->nullable();
            $table->string('pic_province_name')->nullable();
            $table->string('pic_ktp_number')->nullable();
            $table->string('pic_photo')->nullable(); // Foto PIC
            $table->string('pic_ktp_file')->nullable(); // Upload file KTP
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};