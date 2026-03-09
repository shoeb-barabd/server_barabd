<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'first_name'        => 'Super',
                'last_name'         => 'Admin',
                'password'          => '12345678',     // plain; mutator will hash ONCE
                'role'              => 'superadmin',   // ensure role middleware passes
                'email_verified_at' => now(),          // optional
            ]
        );
    }
}
