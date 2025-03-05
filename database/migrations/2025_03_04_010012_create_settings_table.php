<?php

    use App\Models\User;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_prefix', 3)->default('INV');
                $table->string('currency_code', 3)->default('USD');
                $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('settings');
        }
    };
