<?php

namespace Database\Factories;

use App\Models\FeedBack;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
            return [
                'content' => $this->faker->paragraph,
                'feedback_id' => FeedBack::Factory(),
                'user_id'=> User::factory()
            ];
    }
}
