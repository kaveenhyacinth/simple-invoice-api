<?php

    namespace Database\Seeders;

    use App\Models\Client;
    use Illuminate\Database\Seeder;

    class ClientSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            for ($i = 3; $i < 7; $i++) {
                Client::factory()
                    ->create([
                        'address_id' => $i,
                        'status' => 'active'
                    ]);
            }

            Client::factory()
                    ->create([
                        'address_id' => 7,
                        'status' => 'deleted'
                    ]);
        }
    }
