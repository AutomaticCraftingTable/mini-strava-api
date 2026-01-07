<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Roles and Admin first
        $this->call([
            AdminUserSeeder::class,
            RankingSeeder::class,
        ]);

        // 2. Create Test User
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'), // or Hash::make('password')
                'email_verified_at' => now(),
            ]
        );
        
        // 3. Assign 'user' role
        $user->assignRole('user');
    }
}
