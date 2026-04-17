<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            [
                'name' => 'Riyadh',
                'code' => 'RUH',
                'is_available' => true,
            ],
            [
                'name' => 'Jeddah',
                'code' => 'JED',
                'is_available' => true,
            ],
            [
                'name' => 'Dammam',
                'code' => 'DMM',
                'is_available' => true,
            ],
            [
                'name' => 'Mecca',
                'code' => 'MEC',
                'is_available' => true,
            ],
            [
                'name' => 'Medina',
                'code' => 'MED',
                'is_available' => true,
            ],
        ];

        for ($i = 0; $i < \count($cities); $i++) {
            $city = $cities[$i];

            $cityExists = City::where('code', $city['code'])->exists();

            if (!$cityExists) {
                City::create([
                    'name' => $city['name'],
                    'code' => $city['code'],
                    'is_available' => $city['is_available'],
                ]);
            }
        }
    }
}
