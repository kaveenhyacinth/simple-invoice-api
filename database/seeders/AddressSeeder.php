<?php

    namespace Database\Seeders;

    use App\Models\Address;
    use Illuminate\Database\Seeder;

    class AddressSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            Address::factory()
                ->count(2)
                ->create([
                    'type' => 'user'
                ]);

            Address::factory()
                ->count(5)
                ->create([
                    'type' => 'client'
                ]);
        }
    }
