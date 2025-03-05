<?php

    namespace App\Http\Controllers\Api\V1;

    use App\Http\Controllers\Controller;
    use App\Http\Resources\V1\InvoiceCollection;
    use App\Http\Resources\V1\InvoiceResource;
    use App\Models\Invoice;
    use Gate;
    use Illuminate\Auth\Access\AuthorizationException;
    use Illuminate\Http\JsonResponse;
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
        public function index(Request $request): InvoiceCollection
        {
            $invoices = $request->user()
                ->invoices()
                ->with(['client', 'client.address', 'items', 'address', 'paymentTerm'])
                ->paginate(perPage: 10);

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
         *
         * @throws AuthorizationException
         */
        public function show(Invoice $invoice): InvoiceResource|JsonResponse
        {
            $response = Gate::inspect('modify', $invoice);

            if ($response->denied()) {
                return response()->json([
                    'message' => $response->message()
                ], 403);
            }

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
