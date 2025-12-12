<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('minimal_pemesanan')->after('stock')->default(1);
            $table->integer('berat_satuan')->after('minimal_pemesanan')->comment('Berat dalam gram');
            $table->enum('kondisi', ['Baru', 'Bekas'])->after('berat_satuan')->default('Baru');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['minimal_pemesanan', 'berat_satuan', 'kondisi']);
        });
    }
};