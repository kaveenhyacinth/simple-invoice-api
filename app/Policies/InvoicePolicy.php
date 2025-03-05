<?php

    namespace App\Policies;

    use App\Models\Invoice;
    use App\Models\User;
    use Illuminate\Auth\Access\Response;

    class InvoicePolicy
    {
        public function modify(User $user, Invoice $invoice): Response
        {
            return $user->id === $invoice->user_id
                ? Response::allow()
                : Response::deny('You do not own this invoice.');
        }

        public function update(User $user, Invoice $invoice): Response
        {
            $this->modify($user, $invoice);

            return !in_array($invoice->status, ['paid', 'cancelled'])
                ? Response::allow()
                : Response::deny('You cannot update a paid or cancelled invoice.');
        }
    }
