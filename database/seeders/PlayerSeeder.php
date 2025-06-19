<?php

namespace Database\Seeders;

use App\Models\Odf;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=1; $i <= 1000; $i++) { 
            try {
                
                    Odf::create([
                'eventGame' => 'Golf',
                'eventName' => "Women's Individual Stroke Play",
                'eventDetails' => json_encode([
                    "_attributes" => [
                        "Gender" => "W",
                        "EventName" => "Women's Individual Stroke Play",
                        "DisciplineName" => "Golf"
                    ]
                ]),
                'report_date' => now()->toDateString(),
                'report_time' => now()->toTimeString(),
                'reportDetails' => json_encode([
                    "DocumentType" => "DT_STATS",
                    "ResultStatus" => "OFFICIAL",
                    "CompetitionCode" => "OG2024"
                ]),
                'venueName' => 'LGN',
                'venueLocation' => 'Le Golf National',
                'playerId' => rand(1000, 9999),
                'playerName' => fake()->name(),
                'playerDob' => now()->subYears(rand(20, 40))->toDateString(),
                'playerGender' => 'F',
                'playerTeam' => strtoupper(fake()->countryCode()),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            } catch (\Throwable $th) {
                continue;
            }
        }
    }
}
