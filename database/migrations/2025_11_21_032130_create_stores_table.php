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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('pic_name');
            $table->string('pic_phone');
            $table->string('pic_email');
            $table->string('pic_street'); // Nama jalan
            $table->string('pic_rt');
            $table->string('pic_rw');
            $table->string('pic_kelurahan');
            $table->string('pic_city'); // Kabupaten/Kota
            $table->string('pic_province');
            $table->string('pic_ktp_number');
            $table->string('pic_photo')->nullable(); // Foto PIC
            $table->string('pic_ktp_file')->nullable(); // Upload file KTP
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};