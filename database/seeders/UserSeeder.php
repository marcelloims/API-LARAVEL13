<?php

namespace Database\Seeders;

use App\Models\User;
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
        if (config('app.debug') && config('app.env') == 'local') {
            DB::table('users')->truncate();
        }
        $users = [
            [
                'name'              => 'Admin',
                'email'             => 'admin@example.com',
                'email_verified_at' => now(),
                'password'          => bcrypt('password')
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
