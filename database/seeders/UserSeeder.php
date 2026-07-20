<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('app.debug') && config('app.env') == 'local') {
            // Matikan sementara foreign key constraints untuk menghindari error 1701
            Schema::disableForeignKeyConstraints();

            DB::table('posts')->truncate();
            DB::table('users')->truncate();

            // Nyalakan kembali foreign key constraints setelah selesai
            Schema::enableForeignKeyConstraints();
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
