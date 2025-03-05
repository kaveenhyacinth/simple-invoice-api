<?php

    namespace App\Http\Controllers\Api\V1;

    use App\Http\Controllers\Controller;
    use App\Http\Resources\V1\InvoiceCollection;
    use App\Http\Resources\V1\InvoiceResource;
    use App\Models\Invoice;
    use Illuminate\Http\Request;

    class InvoiceController extends Controller
    {
        /**
         * @OA\Get(
         *     path="/invoices",
         *     tags={"Invoices"},
         *     summary="Get the list of invoices (paginated)",
         *     operationId="getInvoiceList",
         *     @OA\Response(
         *         response="200",
         *         description="The invoices"
         *     )
         * )
         */
        public function index(): InvoiceCollection
        {
            $invoices = Invoice::with(['user', 'client', 'client.address', 'items', 'address', 'paymentTerm']
            )->paginate(perPage: 10);
            return new InvoiceCollection($invoices);
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(Request $request)
        {
            //
        }

        /**
         * @OA\Get(
         *     path="/invoices/{invoiceId}",
         *     parameters={
         *     {
         *     "name"="invoiceId",
         *     "in"="path",
         *     "required"=true,
         *     "description"="The id of the invoice",
         *     "schema": {"type"="integer"}
         *     }
         *     },
         *     tags={"Invoices"},
         *     summary="Get an invoice by id",
         *     operationId="getInvoice",
         *     @OA\Response(
         *         response="200",
         *         description="The invoice"
         *     )
         * )
         */
        public function show(Invoice $invoice): InvoiceResource
        {
            return new InvoiceResource($invoice);
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, Invoice $invoice)
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(Invoice $invoice)
        {
            //
        }
    }
