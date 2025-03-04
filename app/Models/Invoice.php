<?php

    namespace App\Models;

    use Database\Factories\InvoiceFactory;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class Invoice extends Model
    {
        /** @use HasFactory<InvoiceFactory> */
        use HasFactory;

        const STATUSES = [
            'draft',
            'pending',
            'paid',
            'cancelled',
        ];

        public function items(): HasMany
        {
            return $this->hasMany(InvoiceItem::class);
        }

        public function paymentTerm(): BelongsTo
        {
            return $this->belongsTo(PaymentTerm::class);
        }

        public function address(): BelongsTo
        {
            return $this->belongsTo(Address::class);
        }

        public function client(): BelongsTo
        {
            return $this->belongsTo(Client::class);
        }

        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }
    }
