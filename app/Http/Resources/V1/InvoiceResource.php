<?php

    namespace App\Http\Resources\V1;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    class InvoiceResource extends JsonResource
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
                'invoiceNumber' => $this->invoice_number,
                'invoiceDate' => $this->invoice_date,
                'invoiceDue' => $this->getDueDate($this),
                'title' => $this->title,
                'description' => $this->description,
                'status' => $this->status,
                'total' => $this->getTotal($this),
                'items' => InvoiceItemResource::collection($this->items),
                'paymentTerm' => new PaymentTermResource($this->paymentTerm),
                'address' => new AddressResource($this->address),
                'client' => new ClientResource($this->client),
            ];
        }

         private function getDueDate($invoice): string
        {
            $invoiceDate = Carbon::parse($invoice->invoice_date);
            $dueDate = $invoiceDate->copy()->addDays((int)$invoice->paymentTerm->term);
            return $dueDate->toDateString();
        }

        private function getTotal($invoice): string
        {
            $total = $invoice->items->sum(fn($item) => $item->quantity * $item->price);
            return number_format($total, 2);
        }
    }
