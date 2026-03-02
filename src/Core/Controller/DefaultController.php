<?php

namespace App\Core\Controller;

use Symfony\Component\HttpFoundation\Response;

class DefaultController extends RestController
{
    public function index(): Response
    {
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
