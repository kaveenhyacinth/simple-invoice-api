<?php

    use App\Models\User;
    use Laravel\Sanctum\Sanctum;

    beforeEach(function () {
        $this->seed();
        $this->user = User::first();
        Sanctum::actingAs($this->user);
    });

    describe('user', function () {
        test('can access the profile', function () {
            $response = $this->get('/api/v1/my/profile');
            $response->assertStatus(200);
        });

        test('can access the settings', function () {
            $response = $this->get('/api/v1/my/settings');
            $response->assertStatus(200);
        });
    });
