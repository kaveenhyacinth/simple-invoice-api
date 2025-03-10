<?php

    namespace Database\Factories;

    use Illuminate\Database\Eloquent\Factories\Factory;

    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
     */
    class AddressFactory extends Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         */
        public function definition(): array
        {
            return [
                'street' => $this->faker->streetAddress(),
                'city' => $this->faker->city(),
                'state' => $this->faker->word(),
                'zip' => $this->faker->postcode(),
                'country' => $this->faker->country(),
                'type' => $this->faker->randomElement(['user', 'client']),
                'user_id' => 1
            ];
        }
    }
