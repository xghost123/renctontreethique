<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@rencontre-ethique.fr'],
            [
                'name' => 'Admin User',
                'mobile' => '0123456789',
                'password' => Hash::make('Admin@12345'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create Moderator User
        User::updateOrCreate(
            ['email' => 'moderator@rencontre-ethique.fr'],
            [
                'name' => 'Moderator User',
                'mobile' => '0187654321',
                'password' => Hash::make('Moderator@12345'),
                'role' => 'moderator',
                'email_verified_at' => now(),
            ]
        );

        // Create Test Member User
        User::updateOrCreate(
            ['email' => 'member@rencontre-ethique.test'],
            [
                'name' => 'Member Test User',
                'mobile' => '0612345678',
                'password' => Hash::make('Member@12345'),
                'role' => 'member',
                'email_verified_at' => now(),
            ]
        );

        echo "\n✅ Admin User Created:\n";
        echo "   Email: admin@rencontre-ethique.fr\n";
        echo "   Password: Admin@12345\n";
        echo "   Role: admin\n\n";

        echo "✅ Moderator User Created:\n";
        echo "   Email: moderator@rencontre-ethique.fr\n";
        echo "   Password: Moderator@12345\n";
        echo "   Role: moderator\n\n";

        echo "✅ Test Member User Created:\n";
        echo "   Email: member@rencontre-ethique.test\n";
        echo "   Password: Member@12345\n";
        echo "   Role: member\n\n";
    }
}
