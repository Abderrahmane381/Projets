<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles using the constants from User model
        Role::create(['name' => User::ROLE_EDITOR]);
        Role::create(['name' => User::ROLE_THEME_RESPONSIBLE]);
        Role::create(['name' => User::ROLE_SUBSCRIBER]);
        Role::create(['name' => User::ROLE_GUEST]);
    }
}
