<?php

    namespace App\Http\Controllers\Api\V1;

    use App\Http\Controllers\Controller;
    use App\Http\Resources\V1\ClientCollection;
    use App\Http\Resources\V1\ClientResource;
    use App\Models\Client;
    use Gate;
    use Illuminate\Auth\Access\AuthorizationException;
    use Illuminate\Http\Request;

    class ClientController extends Controller
    {
        /**
         * @OA\Get(
         *     path="/clients",
         *     tags={"Clients"},
         *     summary="Get the list of clients (paginated)",
         *     operationId="getClientList",
         *     @OA\Response(
         *         response="200",
         *         description="The clients"
         *     )
         * )
         */
        public function index(Request $request): ClientCollection
        {
            $clients = $request->user()
                ->clients()
                ->with(['invoices'])
                ->where('status', '!=', 'deleted')
                ->paginate(10);

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
         * @OA\Get(
         *     path="/clients/{clientId}",
         *     parameters={
         *     {
         *     "name"="clientId",
         *     "in"="path",
         *     "required"=true,
         *     "description"="The id of the client",
         *     "schema": {"type"="integer"}
         *     }
         *     },
         *     tags={"Clients"},
         *     summary="Get a client by id",
         *     operationId="getClient",
         *     @OA\Response(
         *         response="200",
         *         description="The client"
         *     )
         * )
         *
         * @throws AuthorizationException
         */
        public function show(Client $client): ClientResource
        {
            Gate::authorize('modify', $client);
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
