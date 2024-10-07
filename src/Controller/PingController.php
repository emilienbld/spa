<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Attribute\Route;

class PingController
{
    #[Route(path: '/ping', method: 'GET')]
    public function ping(Request $request, Response $response): Response
    {
        $data = ['message' => 'pong'];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
