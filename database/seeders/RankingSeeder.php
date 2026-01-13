<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RankingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Calculate the start of the previous week (Monday) relative to now.
        // This matches the logic in RankingController to ensure activities appear in the ranking.
        $startOfPreviousWeek = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY);

        $usersData = [
            ['name' => 'Alice', 'surname' => 'Racer'],
            ['name' => 'Bob', 'surname' => 'Cyclist'],
            ['name' => 'Charlie', 'surname' => 'Walker'],
            ['name' => 'Diana', 'surname' => 'Sprinter'],
        ];

        foreach ($usersData as $index => $data) {
            $user = User::create([
                'name' => $data['name'],
                'surname' => $data['surname'],
                'email' => strtolower($data['name']) . '.ranking@test.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            $types = ['run', 'bicycle', 'walk'];

            for ($i = 1; $i <= 3; $i++) {
                $activityDate = $startOfPreviousWeek
                    ->copy()
                    ->addDays(rand(0, 6))
                    ->addHours(rand(6, 20));

                $activity = $user->activities()->make([
                    'title' => "{$data['name']}'s Activity #{$i}",
                    'note' => 'Seeded for ranking test',
                    'activity_type' => $types[$i - 1],
                    'distance' => rand(300, 1500) / 100,
                    'time' => rand(1200, 4000),
                    'pace' => 5.30,
                    'speed' => 11.5,
                ]);

                $activity->created_at = $activityDate;
                $activity->updated_at = $activityDate;
                $activity->save();
            }
        }
    }
}