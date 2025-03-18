<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => "admin",
            'email' => "admin@mail.com",
            'password'=>Hash::make('admin123'),
            'role'=>'admin'
        ]);

        DB::table('users')->insert([
            'name' => "Budi",
            'email' => "budi@gmail.com",
            'password'=>Hash::make('budi123'),
            'role'=>'officer'
        ]);
    }
}
