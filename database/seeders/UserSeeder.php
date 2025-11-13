<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua role dari tabel roles
        $roles = DB::table('roles')->get()->keyBy('name');

        // Data default users
        $users = [
            [
                'name' => 'System Administrator',
                'email' => 'admin@easypark.com',
                'password' => 'admineasypark',
                'role' => 'admin',
            ],
            [
                'name' => 'Main Officer',
                'email' => 'officer@easypark.com',
                'password' => 'officereasypark',
                'role' => 'officer',
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@easypark.com',
                'password' => 'usereasypark',
                'role' => 'user',
            ],
        ];

        // Loop create user
        foreach ($users as $user) {
            if (isset($roles[$user['role']])) {
                User::create([
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'password' => Hash::make($user['password']),
                    'role_id' => $roles[$user['role']]->id,
                ]);
            }
        }
    }
}
