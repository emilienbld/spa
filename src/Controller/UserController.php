<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Attribute\Route;
use App\Service\MongoService;

class UserController
{
    private MongoService $mongoService;

    public function __construct()
    {
        $this->mongoService = new MongoService();
    }

    #[Route(path: '/connexion', method: 'POST')]
    public function connexion(Request $request, Response $response): Response
    {
        // Récupérer les données JSON envoyées dans le corps de la requête
        $data = json_decode((string) $request->getBody(), true);
        $email = $data['user'] ?? null;
        $password = $data['password'] ?? null;

        // Vérifier que les paramètres sont fournis
        if (!$email || !$password) {
            $response->getBody()->write(json_encode(['error' => 'Paramètres manquants']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Rechercher l'utilisateur dans la base de données par email
        $user = $this->mongoService->findUserByEmail($email);

        // Vérifie si l'utilisateur existe et si le mot de passe est correct
        if (!$user || !password_verify($password, $user['password'])) {
            $response->getBody()->write(json_encode(['error' => 'Mot de passe ou identifiant incorrect']));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }

        // Retourner les informations utilisateur si les identifiants sont corrects
        $response->getBody()->write(json_encode([
            'status' => 'success',
            'user' => [
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'email' => $user['email'],
                'date_naissance' => $user['date_naissance'] ?? null,
                'adresse' => $user['adresse'] ?? null,
            ]
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    //    Test
//curl -X POST http://localhost:8000/connexion \
//-H "Content-Type: application/json" \
//-d '{"user": "eb@gmail.com", "password": "12345"}'

    #[Route(path: '/inscription', method: 'POST')]
    public function inscription(Request $request, Response $response): Response
    {
        // Récupérer les données JSON envoyées dans le corps de la requête
        $data = json_decode((string) $request->getBody(), true);

        $firstname = $data['firstname'] ?? null;
        $lastname = $data['lastname'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$firstname || !$lastname || !$email || !$password) {
            $response->getBody()->write(json_encode(['error' => 'Paramètres manquants']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Vérifie si l'email est déjà utilisé
        $existingUser = $this->mongoService->findUserByEmail($email);
        if ($existingUser) {
            $response->getBody()->write(json_encode(['error' => 'Email déjà utilisé']));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }

        // Hacher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Créer un tableau avec les données à insérer
        $newUser = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'user',
            'date_naissance' => $data['date_naissance'] ?? null,
            'adresse' =>  [
                'rue' => $data['adresse']['rue'] ?? '',
                'ville' => $data['adresse']['ville'] ?? '',
                'cdp' => $data['adresse']['cdp'] ?? ''
            ]
        ];

        // Insérer l'utilisateur dans la base de données
        $this->mongoService->insertUser($newUser);

        // Retourner une réponse de succès
        $response->getBody()->write(json_encode(['status' => 'User registered successfully']));
        return $response->withHeader('Content-Type', 'application/json');
    }
//    Test
//curl -X POST http://localhost:8000/inscription \
//-H "Content-Type: application/json" \
//-d '{
//    "firstname": "John",
//    "lastname": "Doe",
//    "email": "johndoe@example.com",
//    "password": "password123",
//    "adresse": {
//        "rue": "123 Main St",
//        "ville": "Paris",
//        "cdp": "75001"
//    }
//}'

//curl -X POST http://localhost:8000/inscription \
//-H "Content-Type: application/json" \
//-d '{"firstname": "John", "lastname": "Doe", "email": "johndoe@example.com", "password": "password123"}'


}
