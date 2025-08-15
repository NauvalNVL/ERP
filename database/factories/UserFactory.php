<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 'USER' . str_pad(fake()->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'username' => fake()->unique()->userName(),
            'official_name' => fake()->name(),
            'official_title' => fake()->jobTitle(),
            'mobile_number' => fake()->numerify('08##########'),
            'official_tel' => fake()->numerify('021#######'),
            'password' => static::$password ??= Hash::make('password'),
            'status' => fake()->randomElement(['A', 'O']),
            'password_expiry_date' => fake()->numberBetween(30, 365),
            'amend_expired_password' => fake()->randomElement(['Yes', 'No']),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'password_expiry_date' => 0,
        ]);
    }
}
