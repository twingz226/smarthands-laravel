<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Customer_Id' => 'CUST' . $this->faker->unique()->randomNumber(5, true),
            'Name' => $this->faker->name(),
            'Contact' => $this->faker->phoneNumber(),
            'Email' => $this->faker->unique()->safeEmail(),
            'Address' => $this->faker->address(),
            'Registered_Date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the customer is a corporate client
     */
    public function corporate(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'Name' => $this->faker->company(),
                'Customer_Id' => 'CORP' . $this->faker->unique()->randomNumber(4, true),
            ];
        });
    }

    /**
     * Indicate that the customer is a residential client
     */
    public function residential(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'Customer_Id' => 'RES' . $this->faker->unique()->randomNumber(5, true),
            ];
        });
    }
}