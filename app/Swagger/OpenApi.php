<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Transaction Service API",
    description: "API Documentation for Transaction Service"
)]

#[OA\SecurityScheme(
    securityScheme: "ApiKeyAuth",
<<<<<<< HEAD
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
=======
    type: "apiKey",
    in: "header",
    name: "X-IAE-KEY"
>>>>>>> 2d3a04638b2499e38ca6897529c1c4a8fa88b97a
)]

class OpenApi
{
}