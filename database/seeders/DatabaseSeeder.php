<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        if (!User::where('email', 'trainer@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Trainer Example',
                'email' => 'trainer@example.com',
                'password' => Hash::make('password'),
                'role' => 'trainer',
                'email_verified_at' => now(),
            ]);
            $this->command->info('Utente Trainer di esempio creato.');
        } else {
            $this->command->info('Utente Trainer di esempio già esistente.');
        }


        $this->call(ClientSeeder::class);


    }
}