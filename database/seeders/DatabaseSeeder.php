<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer 5 utilisateurs
        User::factory(5)->create();

        // Créer entre 5 et 10 posts pour chaque utilisateur
        $this->call(PostSeeder::class);
    }
}
