<?php

    namespace App\Http\Controllers;

    use OpenApi\Attributes as OA;

    #[
        OA\Info(version: "0.1", title: "Simple Invoice API"),
        OA\Server(url: "http://invoice-app.test/api/v1", description: 'Development server'),
        OA\SecurityScheme(
            securityScheme: 'bearerAuth',
            type: 'http',
            name: 'Authorization',
            in: 'header',
            scheme: 'bearer'
        )
    ]
    abstract class Controller
    {
        //
    }
