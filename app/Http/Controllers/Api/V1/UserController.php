<?php

    namespace App\Http\Controllers\Api\V1;

    use App\Http\Controllers\Controller;
    use App\Http\Resources\V1\SettingsResource;
    use App\Models\Setting;

    class UserController extends Controller
    {
        public function settings(): SettingsResource
        {
            // TODO: Change once auth implemented
            $settings = Setting::first();
            return new SettingsResource($settings);
        }
    }
