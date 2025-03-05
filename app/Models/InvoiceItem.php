<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class InvoiceItem extends Model
    {
        /** @use HasFactory<\Database\Factories\InvoiceItemFactory> */
        use HasFactory;

        protected $fillable = [
            'name',
            'quantity',
            'price',
            'invoice_id',
        ];

        public function invoice(): BelongsTo
        {
            return $this->belongsTo(Invoice::class);
        }
    }
