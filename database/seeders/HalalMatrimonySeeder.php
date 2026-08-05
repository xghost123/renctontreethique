<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HalalMatrimonySeeder extends Seeder
{
    public function run(): void
    {
        // Packages (zawajuna pricing model)
        DB::table('packages')->insert([
            ['name' => 'Brother Monthly', 'price' => 9.90, 'duration_days' => 30, 'features' => json_encode(['browse', 'proposals', 'chat', 'photos']), 'is_active' => 1],
            ['name' => 'Sister Monthly', 'price' => 3.90, 'duration_days' => 30, 'features' => json_encode(['browse', 'proposals', 'chat', 'photos']), 'is_active' => 1],
            ['name' => 'Brother One-Shot', 'price' => 24.90, 'duration_days' => 90, 'features' => json_encode(['browse', 'proposals', 'chat', 'photos', 'priority']), 'is_active' => 1],
        ]);

        // Admin account (CHANGE PASSWORD BEFORE PRODUCTION)
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@halalmarriage.app',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Screening questions (zawajuna-style)
        DB::table('screening_questions')->insert([
            ['gender' => 'female', 'question' => "Es-tu prête à suivre ton mari, peu importe l'endroit ?", 'sort_order' => 1],
            ['gender' => 'both', 'question' => 'Quelle est ta pratique religieuse quotidienne ?', 'sort_order' => 2],
            ['gender' => 'male', 'question' => 'Acceptes-tu la polygamie ?', 'sort_order' => 3],
            ['gender' => 'female', 'question' => 'Préfères-tu un mari qui a déjà des enfants ?', 'sort_order' => 4],
            ['gender' => 'both', 'question' => "Quel est ton niveau d'étude islamique ?", 'sort_order' => 5],
        ]);
    }
}
