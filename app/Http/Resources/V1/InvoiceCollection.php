<?php

    namespace App\Http\Resources\V1;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;

    class InvoiceCollection extends ResourceCollection
    {
        /**
         * Transform the resource collection into an array.
         *
         * @return array<int|string, mixed>
         */
        public function toArray(Request $request): array
        {
            return $this->collection->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoiceNumber' => $invoice->invoice_number,
                    'invoiceDate' => $invoice->invoice_date,
                    'invoiceDue' => $this->getDueDate($invoice),
                    'title' => $invoice->title,
                    'status' => $invoice->status,
                    'client' => (new ClientResource($invoice->client))['name'],
                    'total' => $this->getTotal($invoice),
                ];
            })->all();
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
