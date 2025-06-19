<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
    
        $trainer = User::updateOrCreate(
            ['email' => 'trainer@example.com'],
            [
                'name' => 'Marco Rossi',
                'password' => Hash::make('password'),
                'role' => 'trainer',
            ]
        );

      
        $clients = [
            [
                'name' => 'Mario Bianchi',
                'email' => 'mario@example.com',
                'password' => Hash::make('password'),
                'role' => 'client',
                'phone' => '3331234567',
                'date_of_birth' => '1985-04-15',
                'gender' => 'male',
                'goals' => 'Perdere peso e aumentare la massa muscolare',
                'preferences' => 'Preferisce allenamenti serali',
                'notes' => 'Allergico a glutine',
            ],
            [
                'name' => 'Luca Verdi',
                'email' => 'luca@example.com',
                'password' => Hash::make('password'),
                'role' => 'client',
                'phone' => '3349876543',
                'date_of_birth' => '1992-11-30',
                'gender' => 'male',
                'goals' => 'Incrementare resistenza cardiovascolare',
                'preferences' => 'Allenamenti mattutini',
                'notes' => '',
            ],
            [
                'name' => 'Anna Neri',
                'email' => 'anna@example.com',
                'password' => Hash::make('password'),
                'role' => 'client',
                'phone' => '3356543210',
                'date_of_birth' => '1990-07-20',
                'gender' => 'female',
                'goals' => 'Tonificare e migliorare la postura',
                'preferences' => 'Allenamenti a giorni alterni',
                'notes' => 'Problemi di schiena da monitorare',
            ],
        ];

        foreach ($clients as $clientData) {
            User::updateOrCreate(
                ['email' => $clientData['email']],
                $clientData
            );
        }

        $this->command->info('Seeder utenti trainer e clienti eseguito con successo!');
    }
}
