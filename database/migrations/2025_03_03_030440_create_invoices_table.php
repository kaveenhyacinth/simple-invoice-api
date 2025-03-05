<?php

    use App\Models\Address;
    use App\Models\Client;
    use App\Models\Invoice;
    use App\Models\PaymentTerm;
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
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->integer('invoice_number')->unique();
                $table->date('invoice_date');
                $table->string('title');
                $table->mediumText('description');
                $table->enum('status', Invoice::STATUSES)->default(Invoice::STATUSES[1]);
                $table->foreignIdFor(PaymentTerm::class);
                $table->foreignIdFor(Address::class);
                $table->foreignIdFor(Client::class);
                $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
                $table->timestamps();
            });

            Schema::create('invoice_items', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('quantity');
                $table->decimal('price');
                $table->foreignIdFor(Invoice::class)->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('invoices');
            Schema::dropIfExists('invoice_items');
        }
    };
