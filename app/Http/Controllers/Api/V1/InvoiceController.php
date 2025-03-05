<?php

    namespace App\Http\Controllers\Api\V1;

    use App\Http\Controllers\Controller;
    use App\Http\Resources\V1\InvoiceCollection;
    use App\Http\Resources\V1\InvoiceResource;
    use App\Models\Invoice;
    use Exception;
    use Gate;
    use Illuminate\Auth\Access\AuthorizationException;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Random\RandomException;
    use Throwable;

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
         * @OA\Post(
         *     path="/invoices",
         *     tags={"Invoices"},
         *     summary="Create a new invoice",
         *     operationId="createInvoice",
         *     @OA\RequestBody(
         *     required=true,
         *     @OA\JsonContent(
         *     required={"title", "description", "invoiceDate", "paymentTermId", "clientId", "addressId", "items"},
         *     @OA\Property(property="title", type="string", example="Invoice #1"),
         *     @OA\Property(property="description", type="string", example="This is the first invoice"),
         *     @OA\Property(property="invoiceDate", type="string", format="date", example="2021-10-01"),
         *     @OA\Property(property="paymentTermId", type="integer", example="1"),
         *     @OA\Property(property="clientId", type="integer", example="1"),
         *     @OA\Property(property="addressId", type="integer", example="1"),
         *     @OA\Property(property="status", type="string", example="draft"),
         *     @OA\Property(property="items", type="array", @OA\Items(
         *     @OA\Property(property="name", type="string", example="Item #1"),
         *     @OA\Property(property="quantity", type="integer", example="1"),
         *     @OA\Property(property="price", type="number", example="100.00")
         *    ))
         *  )
         * ),
         *     @OA\Response(
         *     response="201",
         *     description="Invoice created"
         * ),
         *     @OA\Response(
         *     response="500",
         *     description="Invoice not created"
         * )
         * )
         */
        public function store(Request $request)
        {
            $request->merge([
                'client_id' => $request->input('clientId'),
                'address_id' => $request->input('addressId'),
                'payment_term_id' => $request->input('paymentTermId'),
                'invoice_date' => $request->input('invoiceDate'),
            ]);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'invoice_date' => 'required|date',
                'payment_term_id' => 'required|exists:payment_terms,id',
                'client_id' => 'required|exists:clients,id',
                'address_id' => 'required|exists:addresses,id',
                'status' => 'in:draft,pending,paid,cancelled',
            ]);

            $validatedItems = $request->validate([
                'items' => 'required|array',
                'items.*.name' => 'required|string|max:255',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
            ]);

            try {
                $validated['invoice_number'] = $this->getUniqueInvoiceNumber();
                $invoice = $request->user()->invoices()->create($validated);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Invoice not created'
                ], 500);
            }

            try {
                $invoice->items()->createMany($validatedItems['items']);
            } catch (Exception $e) {
                $invoice->delete();
                return response()->json([
                    'message' => 'Invoice items not created'
                ], 500);
            }


            return response()->json([
                'message' => 'Invoice created',
                'invoice' => $invoice->id
            ], 201);
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
         * @OA\PUT(
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
         *     summary="Update an invoice",
         *     operationId="updateInvoice",
         *     @OA\RequestBody(
         *     required=true,
         *     @OA\JsonContent(
         *     required={"title", "description", "invoiceDate", "paymentTermId", "addressId", "items"},
         *     @OA\Property(property="title", type="string", example="Invoice #1"),
         *     @OA\Property(property="description", type="string", example="This is the first invoice"),
         *     @OA\Property(property="invoiceDate", type="string", format="date", example="2021-10-01"),
         *     @OA\Property(property="paymentTermId", type="integer", example="1"),
         *     @OA\Property(property="addressId", type="integer", example="1"),
         *     @OA\Property(property="items", type="array", @OA\Items(
         *     @OA\Property(property="id", type="integer", example="(int)1 or null for new items"),
         *     @OA\Property(property="name", type="string", example="Item #1"),
         *     @OA\Property(property="quantity", type="integer", example="1"),
         *     @OA\Property(property="price", type="number", example="100.00")
         *   ))
         * )
         * ),
         *     @OA\Response(
         *     response="200",
         *     description="Invoice updated"
         * ),
         *     @OA\Response(
         *     response="500",
         *     description="Invoice not updated"
         * )
         * )
         * )
         *
         * @throws AuthorizationException
         */
        public function update(Request $request, Invoice $invoice)
        {
            Gate::authorize('update', $invoice);

            $request->merge([
                'address_id' => $request->input('addressId'),
                'payment_term_id' => $request->input('paymentTermId'),
                'invoice_date' => $request->input('invoiceDate'),
            ]);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'invoice_date' => 'required|date',
                'payment_term_id' => 'required|exists:payment_terms,id',
                'address_id' => 'required|exists:addresses,id',
            ]);

            $validatedItems = $request->validate([
                'items' => 'required|array',
                'items.*.id' => 'integer|nullable',
                'items.*.name' => 'required|string|max:255',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
            ]);

            try {
                $invoice->update($validated);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Invoice not updated'
                ], 500);
            }

            try {
                foreach ($validatedItems['items'] as $item) {
                    $invoice->items()->updateOrCreate(['id' => $item['id']], $item);
                }
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Invoice items not updated'
                ], 500);
            }

            return new InvoiceResource($invoice);
        }

        /**
         * @OA\DELETE(
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
         *     summary="Delete an invoice",
         *     operationId="deleteInvoice",
         *     @OA\Response(
         *     response="200",
         *     description="Invoice deleted"
         * ),
         *     @OA\Response(
         *     response="500",
         *     description="Invoice not deleted"
         * )
         * )
         * )
         * @throws Throwable
         */
        public function destroy(Invoice $invoice)
        {
            Gate::authorize('modify', $invoice);

            $invoice->deleteOrFail();

            return response()->json([
                'message' => 'Invoice deleted'
            ]);
        }

        /**
         * @throws RandomException
         */
        protected function getUniqueInvoiceNumber(): int
        {
            do {
                $invoiceNumber = random_int(100000, 999999);
            } while (Invoice::where('invoice_number', $invoiceNumber)->exists());

            return $invoiceNumber;
        }
    }
