<?php

    namespace App\Http\Controllers\Api\V1;

    use App\Http\Controllers\Controller;
    use App\Http\Resources\V1\ClientCollection;
    use App\Http\Resources\V1\ClientResource;
    use App\Models\Client;
    use Illuminate\Http\Request;

    class ClientController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index(): ClientCollection
        {
            $clients = Client::with(['invoices'])->where('status', '!=', 'deleted')->paginate(10);
            return new ClientCollection($clients);
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
        public function show(Client $client): ClientResource
        {
            return new ClientResource($client);
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, Client $client)
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(Client $client)
        {
            //
        }
    }
