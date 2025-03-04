<?php

    namespace Database\Factories;

    use App\Models\Client;
    use App\Models\Invoice;
    use App\Models\PaymentTerm;
    use Illuminate\Database\Eloquent\Factories\Factory;

    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
     */
    class InvoiceFactory extends Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         */
        public function definition(): array
        {
            return [
                'invoice_number' => $this->generateUniqueInvoiceNumber(),
                'invoice_date' => $this->faker->dateTimeThisYear(),
                'title' => $this->faker->sentence(),
                'description' => $this->faker->paragraph(2),
                'status' => $this->faker->randomElement(Invoice::STATUSES),
                'payment_term_id' => $this->faker->numberBetween(1, PaymentTerm::count()),
                'address_id' => $this->faker->numberBetween(1, 2),
                'client_id' => $this->faker->numberBetween(1, Client::count()),
                'user_id' => 1,
            ];
        }

        /**
         * Generate a unique invoice number.
         *
         * @return string
         */
        protected function generateUniqueInvoiceNumber(): string
        {
            return $this->faker->unique()->numberBetween(100000, 999999);
        }
    }
