<?php

    namespace App\Models;

    use Database\Factories\ClientFactory;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class Client extends Model
    {
        /** @use HasFactory<ClientFactory> */
        use HasFactory;

        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }

        public function address(): BelongsTo
        {
            return $this->belongsTo(Address::class);
        }

        public function invoices(): HasMany
        {
            return $this->hasMany(Invoice::class);
        }
    }
