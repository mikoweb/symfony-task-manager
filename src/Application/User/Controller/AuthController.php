<?php

namespace App\Application\User\Controller;

use OpenApi\Attributes as OA;

final class AuthController
{
    #[OA\Tag(name: 'User')]
    #[OA\Post(
        summary: 'User login',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['username', 'password'],
                properties: [
                    new OA\Property(property: 'username', type: 'string', example: 'admin@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns JWT token',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string', example: 'eyJhbGciOiJIUzI...'),
                    ]
                )
            ),
        ]
    )]
    public function loginCheck(): void
    {
    }
}
