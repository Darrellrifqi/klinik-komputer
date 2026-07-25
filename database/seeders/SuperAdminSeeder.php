<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'pagiaxioo@gmail.com'],
            [
                'name'     => 'Super Admin',
                'email'    => 'pagiaxioo@gmail.com',
                'password' => Hash::make('superaxioo'),
                'role'     => 'superadmin',
                'status'   => 'active',
                'phone'    => '081390727420',
            ]
        );

        User::updateOrCreate(
            ['email' => 'produksi@gmail.com'],
            [
                'name'     => 'Tim Produksi',
                'email'    => 'produksi@gmail.com',
                'password' => Hash::make('produksiaxioo'),
                'role'     => 'produksi',
                'status'   => 'active',
                'phone'    => '081234567890',
            ]
        );
    }
}
