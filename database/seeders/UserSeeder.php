<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin1234'),
            'role' => 1
        ]);
        User::create([
            'name' => 'Warehouse Staff',
            'email' => 'staff@mail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('staff1234'),
            'role' => 2
        ]);
    }
}
