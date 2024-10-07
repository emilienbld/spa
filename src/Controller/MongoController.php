<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Attribute\Route;
use App\Service\MongoService;

class MongoController
{
    private MongoService $mongoService;

    public function __construct()
    {
        $this->mongoService = new MongoService();
    }

    #[Route(path: '/test-mongo', method: 'GET')]
    public function testMongoConnection(Request $request, Response $response): Response
    {
        $user = $this->mongoService->getUsersCollection()->findOne([]);
        $data = $user ? ['status' => 'success', 'user' => $user] : ['status' => 'fail', 'message' => 'Aucun utilisateur trouvé'];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
