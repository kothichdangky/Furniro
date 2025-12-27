<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('users')->insert(array (
            array (
                'id' => 1,
                'name' => 'Thắng nè',
                'email' => 'thangsos8900@gmail.com',
                'provider' => NULL,
                'provider_id' => NULL,
                'role' => 'admin',
                'email_verified_at' => NULL,
                'password' => bcrypt('123456'),
                'remember_token' => NULL,
            ),
        ));
    }
}
