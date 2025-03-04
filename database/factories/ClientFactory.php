<?php

    namespace Database\Factories;

    use Illuminate\Database\Eloquent\Factories\Factory;

    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
     */
    class ClientFactory extends Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         */
        public function definition(): array
        {
            return [
                'name' => $this->faker->name(),
                'email' => $this->faker->safeEmail(),
                'address_id' => $this->faker->numberBetween(2, 4),
                'status' => $this->faker->randomElement(['active', 'deleted']),
                'user_id' => 1,
            ];
        }
    }
