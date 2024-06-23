<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'      => 'John Doe',
            'email'     => 'johndoe@sample.com',
            'type'      => 'seller',
            'code'      => 'USR-00001-123',
            'password'  => 'pass!pass!'
        ]);
    }
}
