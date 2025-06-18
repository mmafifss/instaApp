<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'image_path' => 'posts/' . $this->faker->image('storage/app/public/posts', 640, 480, null, false),
            'caption' => $this->faker->sentence(),
        ];
    }
}
