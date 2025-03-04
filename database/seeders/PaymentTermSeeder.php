<?php

    namespace Database\Seeders;

    use App\Models\PaymentTerm;
    use Illuminate\Database\Seeder;

    class PaymentTermSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            $terms = ["1", "7", "14", "30"];
            foreach ($terms as $term) {
                PaymentTerm::factory()
                    ->create([
                        'term' => $term
                    ]);
            }
        }
    }
