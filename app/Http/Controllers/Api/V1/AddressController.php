<?php

    namespace App\Http\Controllers\Api\V1;

    use App\Http\Controllers\Controller;
    use App\Http\Resources\V1\AddressCollection;
    use App\Http\Resources\V1\AddressResource;
    use App\Models\Address;
    use Exception;
    use Gate;
    use Illuminate\Http\Request;

    class AddressController extends Controller
    {
        /**
         * @OA\Get(
         *     path="/addresses",
         *     tags={"Addresses"},
         *     summary="Get the list of addresses",
         *     operationId="getAddressList",
         *     @OA\Response(
         *         response="200",
         *         description="The addresses"
         *     )
         * )
         */
        public function index(Request $request): AddressCollection
        {
            $addresses = $request->user()->addresses()
                ->where('type', '=', 'user')
                ->paginate(10);
            return new AddressCollection($addresses);
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(Request $request)
        {
            $request->merge(['user_id' => $request->user()->id]);

            $validated = $request->validate([
                'street' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'zip' => 'required|string',
                'country' => 'required|string',
            ]);

            try {
                $validated['type'] = 'user';
                $request->user()->addresses()->create($validated);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'An error occurred while creating the address',
                    'error' => $e->getMessage()
                ], 500);
            }

            return response()->json([
                'message' => 'Address created successfully'
            ], 201);
        }

        /**
         * Display the specified resource.
         */
        public function show(Address $address)
        {
            Gate::authorize('modify', $address);

            return new AddressResource($address);
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, Address $address)
        {
            Gate::authorize('modify', $address);

            $validated = $request->validate([
                'street' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'zip' => 'required|string',
                'country' => 'required|string',
            ]);

            try {
                $address->update($validated);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'An error occurred while updating the address',
                    'error' => $e->getMessage()
                ], 500);
            }

            return response()->json([
                'message' => 'Address updated successfully'
            ]);
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(Address $address)
        {
            Gate::authorize('modify', $address);
            
            try {
                $address->delete();
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'An error occurred while deleting the address',
                    'error' => $e->getMessage()
                ], 500);
            }

            return response()->json([
                'message' => 'Address deleted successfully'
            ]);
        }
    }
