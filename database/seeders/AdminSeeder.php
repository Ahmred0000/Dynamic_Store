<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name'     => 'مدير النظام',
            'email'    => 'admin@store.com',
            'password' => Hash::make('admin123456'),
        ]);

        $admin->assignRole('admin');
    }
}
