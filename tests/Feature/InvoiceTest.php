<?php

    use App\Models\User;
    use Laravel\Sanctum\Sanctum;

    beforeEach(function () {
        $this->seed();
        $this->user = User::first();
        Sanctum::actingAs($this->user);
    });

    describe('invoice', function () {
        test('can all be listed', function () {
            $response = $this->get('/api/v1/invoices');
            $response->assertStatus(200);
        });

        test('can be listed', function () {
            $response = $this->get('/api/v1/invoices/1');
            $response->assertStatus(200);
        });
    });
