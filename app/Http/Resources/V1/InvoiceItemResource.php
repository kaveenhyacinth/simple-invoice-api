<?php

    namespace App\Http\Resources\V1;

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    class InvoiceItemResource extends JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @return array<string, mixed>
         */
        public function toArray(Request $request): array
        {
            return [
                'id' => $this->id,
                'invoiceId' => $this->invoice_id,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'price' => $this->price,
                'total' => $this->getTotal($this),
            ];
        }

        private function getTotal($invoiceItem): string
        {
            $total = $invoiceItem->quantity * $invoiceItem->price;
            return number_format($total, 2);
        }
    }
