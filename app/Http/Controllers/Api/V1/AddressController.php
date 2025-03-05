<?php

    namespace App\Http\Controllers\Api\V1;

    use App\Http\Controllers\Controller;
    use App\Http\Resources\V1\AddressCollection;
    use App\Models\Address;
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
            //
        }

        /**
         * Display the specified resource.
         */
        public function show(Address $address)
        {
            //
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, Address $address)
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(Address $address)
        {
            //
        }
    }
