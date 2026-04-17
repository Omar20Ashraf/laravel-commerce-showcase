<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'name' => 'service',
                'is_available' => true,
            ],
            [
                'name' => 'subscription',
                'is_available' => true,
            ],
        ];

        for ($i = 0; $i < \count($modules); $i++) {
            $module = $modules[$i];
            $moduleExists = Module::where('name', $module['name'])->exists();

            if (!$moduleExists) {
                Module::create([
                    'name' => $module['name'],
                    'is_available' => $module['is_available'],
                ]);
            }
        }
    }
}
