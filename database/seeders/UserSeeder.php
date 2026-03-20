<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');
        
        $user = User::create([
            'name' => 'user',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');
    }
}
