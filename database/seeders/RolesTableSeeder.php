<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
            ],
        ];

        for ($i = 0; $i < \count($roles); $i++) {
            $role = $roles[$i];

            $roleExists = Role::where('name', $role['name'])->exists();

            if (!$roleExists) {
                Role::create([
                    'name' => $role['name'],
                ]);
            }
        }
    }
}
