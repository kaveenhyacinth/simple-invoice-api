<?php

    namespace Database\Seeders;

    use App\Models\Setting;
    use App\Models\User;
    use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

    class DatabaseSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         */
        public function run(): void
        {
            Setting::factory()->create([
                'user_id' => User::factory()->create([
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                ])->id,
            ]);

            $this->call([
                AddressSeeder::class,
                ClientSeeder::class,
                PaymentTermSeeder::class,
                InvoiceSeeder::class,
                InvoiceItemSeeder::class,
            ]);
        }
    }
