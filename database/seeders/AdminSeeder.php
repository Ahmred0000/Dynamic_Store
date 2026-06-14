<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate([
            'name'     => 'مدير النظام',
            'email'    => 'Ahmed@store.com',
            'password' => Hash::make('ahmed123456'),
        ]);

        $admin->assignRole('admin');
    }
}
