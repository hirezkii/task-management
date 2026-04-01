<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Rezki',
            'email' => 'rezki@test.com',
            'password' => Hash::make('123456'),
        ]);
        User::create([
            'name' => 'Hidayat',
            'email' => 'hidayat@test.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
