<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed 10 users
        User::factory()->count(10)->create();

        // Create a specific user
        User::create([
            'name' => 'Matheus Felizardo',
            'email' => 'matheus.felizardo2@gmail.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
