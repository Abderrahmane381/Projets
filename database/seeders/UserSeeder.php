<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create editor
        $editor = User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $editor->assignRole(User::ROLE_EDITOR);

        // Create theme responsible
        $themeResponsible = User::create([
            'name' => 'Theme Manager',
            'email' => 'theme@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $themeResponsible->assignRole(User::ROLE_THEME_RESPONSIBLE);

        // Create subscriber
        $subscriber = User::create([
            'name' => 'Test Subscriber',
            'email' => 'subscriber@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $subscriber->assignRole(User::ROLE_SUBSCRIBER);
    }
}
