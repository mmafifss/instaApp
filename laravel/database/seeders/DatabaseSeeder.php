<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 123
        ]);

        \App\Models\Post::factory(10)->create()->each(function ($post) {
            \App\Models\Like::factory(rand(0, 5))->create(['post_id' => $post->id]);
            \App\Models\Comment::factory(rand(0, 5))->create(['post_id' => $post->id]);
        });
    }
}
