<?php
// database/migrations/2024_12_11_000000_add_verification_columns_to_stores_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('pic_ktp_file');
            $table->timestamp('verified_at')->nullable()->after('verification_status');
            $table->foreignId('verified_by')->nullable()->constrained('users')->after('verified_at');
            $table->text('rejection_reason')->nullable()->after('verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['verification_status', 'verified_at', 'verified_by', 'rejection_reason']);
        });
    }
};