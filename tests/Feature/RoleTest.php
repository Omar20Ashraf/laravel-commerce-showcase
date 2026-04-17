<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_roles_can_be_seeded(): void
    {
        $this->seed(RolesTableSeeder::class);

        $this->assertDatabaseHas('roles', [
            'name' => 'admin',
        ]);
    }

    public function test_users_can_be_seeded_with_role(): void
    {
        $this->seed(RolesTableSeeder::class);
        $this->seed(UsersTableSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@admin.com',
        ]);

        $user = User::where('email', 'admin@admin.com')->first();
        $this->assertNotNull($user->role_id);
        $this->assertEquals('admin', $user->role->name);
    }

    public function test_user_role_relationship(): void
    {
        $role = Role::factory()->create(['name' => 'test-role']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->assertEquals('test-role', $user->role->name);
        $this->assertTrue($role->users->contains($user));
    }
}
