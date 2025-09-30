<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temporarily disable hashing: Delete existing admin if exists, create with plain-text password
        User::where('name', 'admin')->delete();
        User::create([
            'name' => 'admin',
            'password' => 'admin123', // Plain-text for temporary access; re-enable hashing later
            'role' => 'admin',
        ]);

        // Seed default settings
        Setting::setValue('radius', '50');
        Setting::setValue('geolocation_timeout', '20000');
        Setting::setValue('max_age', '0');
        Setting::setValue('enable_high_accuracy', 'true');
        Setting::setValue('scan_cooldown', '10');
    }
}
