<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AddressPolicy
{
    public function modify(User $user, Address $address): bool
    {
        return $user->id === $address->user_id;
    }
}
