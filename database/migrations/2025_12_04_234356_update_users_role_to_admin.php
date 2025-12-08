<?php
// database/migrations/xxxx_xx_xx_update_users_role_to_admin.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update buyer -> seller
        DB::table('users')
            ->where('role', 'buyer')
            ->update(['role' => 'seller']);

        // Ubah enum: buyer, seller -> seller, admin
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('seller', 'admin') NOT NULL DEFAULT 'seller'");
    }

    public function down(): void
    {
        DB::table('users')
            ->where('role', 'admin')
            ->update(['role' => 'buyer']);

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('buyer', 'seller') NOT NULL DEFAULT 'buyer'");
    }
};