<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use RuntimeException;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = config('services.super_admin.name');
        $email = config('services.super_admin.email');
        $password = config('services.super_admin.password');

        if (!$name || !$email || !$password) {
            throw new RuntimeException(
                'Super Admin credentials are missing from the .env file.'
            );
        }

        User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'role' => User::ROLE_SUPER_ADMIN,
                'company_id' => null,
            ]
        );
    }
}
