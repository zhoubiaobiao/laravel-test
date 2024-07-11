<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
           [
               'name' => 'admin',
               'email' => 'admin@gmail.com',
               'password' => bcrypt('123456'),
               'role' => 'admin'
           ],
            [
                'name' => 'customer',
                'email' => 'customer@gmail.com',
                'password' => bcrypt('123456'),
                'role' => 'customer'
            ],
        ]);
    }
}
