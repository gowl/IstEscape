<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = $this->faker;

        return [
            'name' => $faker->name(),
            'email' => $faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$WBcxd8ewQSBJKfc25mnKoeBlXtB1vwOrCXAe.krGg8PrpiKXIwtcG', // password
            'remember_token' => Str::random(10),
            'dob' => $faker->dateTimeBetween('1990-01-01', '2005-12-31')
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(
            function (array $attributes) {
                return [
                    'email_verified_at' => null,
                ];
            }
        );
    }
}
