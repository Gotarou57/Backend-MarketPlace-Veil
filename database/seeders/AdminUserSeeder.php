<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah admin sudah ada
        $adminExists = User::where('email', 'admin@marketplace.com')->first();

        if ($adminExists) {
            $this->command->info('Admin user already exists. Skipping...');
            return;
        }

        // Buat user admin
        $admin = User::create([
            'name' => 'Admin Marketplace',
            'email' => 'admin@veil.com',
            'password' => Hash::make('admin123'), // Password default
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin Headquarters, Jakarta',
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@veil.com');
        $this->command->info('Password: admin123');
        $this->command->line('');
        $this->command->warn('⚠️  IMPORTANT: Please change the admin password after first login!');
    }
}