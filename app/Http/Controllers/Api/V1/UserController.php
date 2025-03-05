<?php

    namespace App\Http\Controllers\Api\V1;

    use App\Http\Controllers\Controller;
    use App\Http\Resources\V1\SettingsResource;
    use App\Models\Setting;

    class UserController extends Controller
    {
        /**
         * @OA\Get(
         *     path="/my/settings",
         *     tags={"My"},
         *     summary="Get the user settings",
         *     operationId="getMySettings",
         *     @OA\Response(
         *         response="200",
         *         description="Settings"
         *     )
         * )
         */
        public function settings(): SettingsResource
        {
            //TODO: Change implementation once authentication is implemented
            $settings = Setting::first();
            return new SettingsResource($settings);
        }
    }
