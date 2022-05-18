<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Support>
 */
class SupportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $statusOptions = ['P','A','C'];
        return [
            'user_id' => User::all()->random()->id,
            'lesson_id' => Lesson::all()->random()->id,
            'status' => $statusOptions[array_rand($statusOptions)],
            'description' => $this->faker->sentence(20)
        ];
    }
}
