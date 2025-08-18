<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Enums\Role as RoleEnum;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $adminUser = User::factory()
            ->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
            ]);

        $adminUser->role()->associate(Role::findByEnum(RoleEnum::Admin))->save();

        $testUser = User::factory()
            ->create([
                'name' => 'Test User',
                'email' => 'user@example.com',
            ]);

        $testUser->role()->associate(Role::findByEnum(RoleEnum::User))->save();
    }
}
