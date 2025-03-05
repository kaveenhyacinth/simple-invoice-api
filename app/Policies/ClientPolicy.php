<?php

    namespace App\Policies;

    use App\Models\Client;
    use App\Models\User;
    use Illuminate\Auth\Access\Response;

    class ClientPolicy
    {
        public function modify(User $user, Client $client): Response
        {
            return $user->id === $client->user_id
                ? Response::allow()
                : Response::deny('You do not own this client.');
        }
    }
