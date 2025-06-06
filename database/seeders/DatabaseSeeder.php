<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        \App\Models\Category::create([
            'name' => 'Общее',
            'slug' => 'general',
        ]);

        \App\Models\Setting::updateOrCreate(
            ['key' => 'organizer_share_percent'],
            ['value' => '70']
        );
        \App\Models\Setting::updateOrCreate(
            ['key' => 'repeat_discount_percent'],
            ['value' => '40']
        );
        \App\Models\Setting::updateOrCreate(
            ['key' => 'default_access_days'],
            ['value' => '30']
        );
    }
}
