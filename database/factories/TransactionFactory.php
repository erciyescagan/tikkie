<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'amount' => fake()->randomFloat(2, 100, 2000),
            'from' => fake()->randomElement([fake()->email(), 20]),
            'to' => fake()->randomElement([19, 20]),
            'via' => fake()->randomElement(['Credit Card', 'Wallet'])



        ];
    }
}
