<?php

namespace Database\Seeders;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'id' => '7',
            'name' => 'Tri Anggoro Kasih',
            'email' => 'triangkas@gmail.com',
            'username' => 'admin',
            'password' => bcrypt('password123!'),
            'access_token' => base64_encode('Tri Anggoro Kasih'),
        ]);
    }
}
