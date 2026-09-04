<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin (Teknis)
        User::create([
            'name' => 'Rama Administrator IT',
            'email' => 'admin@mpdpp.banjarmasinkota.go.id',
            'peran' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // 2. INVESTIGATOR 1 (Hambali)
        User::create([
            'name' => 'Hambali Investigator',
            'email' => 'hambali@mpdpp.banjarmasinkota.go.id',
            'peran' => 'investigator',
            'password' => Hash::make('password'),
        ]);

        // 3. INVESTIGATOR 2 (Pahmi)
        User::create([
            'name' => 'Pahmi Investigator',
            'email' => 'pahmi@mpdpp.banjarmasinkota.go.id',
            'peran' => 'investigator',
            'password' => Hash::make('password'),
        ]);

        // 4. Verifikator
        User::create([
            'name' => 'Zee Verifikator',
            'email' => 'verifikator@mpdpp.banjarmasinkota.go.id',
            'peran' => 'verifikator',
            'password' => Hash::make('password'),
        ]);

        // 6. Pelapor 
        User::create([
            'name' => 'Mas Fauzi Pelapor',
            'email' => 'pelapor@gmail.com',
            'peran' => 'pelapor',
            'password' => Hash::make('password'),
        ]);
    }
}