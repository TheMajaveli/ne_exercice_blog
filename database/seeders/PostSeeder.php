<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les utilisateurs
        $users = User::all();

        // Pour chaque utilisateur, créer entre 5 et 10 posts
        foreach ($users as $user) {
            $postCount = rand(5, 10);
            
            Post::factory($postCount)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
