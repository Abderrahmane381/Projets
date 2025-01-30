<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Theme;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Run role seeder first to ensure roles exist
        $this->call(RoleSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('users')->truncate();
        DB::table('themes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Create Editor
        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@techhorizons.com',
            'password' => Hash::make('password123'),
            'is_active' => true
        ]);
        $editor->assignRole(User::ROLE_EDITOR);

        // Create Theme Responsibles
        $responsibles = [];
        foreach ([
            ['AI Manager', 'ai@techhorizons.com'],
            ['IoT Manager', 'iot@techhorizons.com'],
            ['Security Manager', 'security@techhorizons.com']
        ] as [$name, $email]) {
            $responsible = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password123'),
                'is_active' => true
            ]);
            $responsible->assignRole(User::ROLE_THEME_RESPONSIBLE);
            $responsibles[] = $responsible;
        }

        // Create Themes
        foreach ([
            ['Artificial Intelligence', 'Explore the latest developments in AI and machine learning.', 0],
            ['Internet of Things', 'Discover how connected devices are transforming our world.', 1],
            ['Cybersecurity', 'Stay updated on digital security and privacy.', 2]
        ] as [$name, $description, $index]) {
            Theme::create([
                'name' => $name,
                'description' => $description,
                'responsible_id' => $responsibles[$index]->id,
                'is_active' => true
            ]);
        }

        // Create Subscribers
        foreach (range(1, 5) as $i) {
            $subscriber = User::create([
                'name' => "Subscriber $i",
                'email' => "subscriber{$i}@example.com",  // Fixed email format
                'password' => Hash::make('password123'),
                'is_active' => true
            ]);
            $subscriber->assignRole(User::ROLE_SUBSCRIBER);
        }
    }
}